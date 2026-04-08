<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\PsychologyAppointment;
use App\Notifications\PaymentSuccessNotification;
use App\Services\Payments\PaymentService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    use ApiResponse;

    public function __construct(protected PaymentService $paymentService) {}

    /**
     * معالجة كافة إشعارات الدفع (Webhooks & Callbacks) من كافة الشركات
     */
    public function handle(Request $request, string $provider): JsonResponse
    {
        try {
            $providerInstance = $this->paymentService->getProvider($provider);

            // التحقق من صحة الإشعار (HMAC / Signature / Checksum)
            if (! $providerInstance->verify($request)) {
                return $this->errorResponse('Invalid Webhook Signature', 401);
            }

            // تحديد رقم الطلب (Order ID / Transaction ID)
            $providerId = $request->input('order') ?? $request->input('id') ?? $request->query('token');

            // تحديث سجل الدفع في قاعدة البيانات
            $payment = Payment::where('provider_id', $providerId)
                ->where('provider', $provider)
                ->firstOrFail();

            if ($payment->status === 'success') {
                return $this->successResponse(null, 'Payment already processed');
            }

            $payment->update([
                'status' => 'success',
                'payment_id' => $request->input('id') ?? $request->query('payment_id'),
                'payload' => $request->all(),
            ]);

            // تنفيذ الإجراء المطلوب (تفعيل الكورس أو الاستشارة)
            $this->finalizePayment($payment);

            return $this->successResponse(null, 'Payment verified and processed');
        } catch (Exception $e) {
            return $this->errorResponse('Webhook Error: '.$e->getMessage(), 400);
        }
    }

    /**
     * الإجراء النهائي بعد نجاح الدفع (تفعيل الخدمات)
     */
    protected function finalizePayment(Payment $payment): void
    {
        $payable = $payment->payable;

        if ($payable instanceof Course) {
            // تسجيل الطالب في الكورس تلقائياً
            Enrollment::updateOrCreate([
                'user_id' => $payment->user_id,
                'course_id' => $payable->id,
            ], [
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }

        if ($payable instanceof PsychologyAppointment) {
            // تأكيد الموعد النفسي تلقائياً
            $payable->update(['status' => 'confirmed']);
        }

        // إرسال إشعار النجاح للمستخدم (بريد + قاعدة بيانات)
        $payment->user->notify(new PaymentSuccessNotification($payment));
    }
}
