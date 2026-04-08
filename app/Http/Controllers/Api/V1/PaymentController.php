<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Services\Payments\PaymentService;
use App\Models\Invoice;
use App\Models\Order;
use App\Traits\BaseApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use BaseApiResponse;

    public function __construct(protected PaymentService $paymentService) {}

    /**
     * بدء عملية الدفع (Checkout)
     *
     * @param  Request  $request  [provider, type, id]
     */
    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'provider' => 'required|string|in:paymob,stripe,paypal,kashier',
            'type' => 'required|string|in:invoice,order',
            'id' => 'required|integer',
        ]);

        try {
            // تحديد الغرض من الدفع (Model)
            $payable = match ($request->type) {
                'invoice' => Invoice::findOrFail($request->id),
                'order' => Order::findOrFail($request->id),
            };

            // التحقق من السعر
            $amount = $payable->total_amount ?? $payable->amount;
            $currency = $request->provider === 'paypal' ? 'USD' : 'EGP';

            // إنشاء الدفع والحصول على الرابط
            $result = $this->paymentService->createPayment(
                $payable,
                $request->provider,
                (float) $amount,
                $currency
            );

            return $this->successResponse($result, 'Payment initiated successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Payment Failed: '.$e->getMessage(), 400);
        }
    }
}
