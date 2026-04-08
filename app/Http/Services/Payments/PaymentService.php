<?php

namespace App\Http\Services\Payments;

use App\Contracts\PaymentProvider;
use App\Models\Payment;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * التحصل على الـ Provider المناسب للمدخر النوع (Stripe, Paymob, etc.)
     */
    public function getProvider(string $providerName): PaymentProvider
    {
        return match (strtolower($providerName)) {
            'paymob' => new PaymobProvider,
            'stripe' => new StripeProvider,
            'paypal' => new PayPalProvider,
            'kashier' => new KashierProvider,
            default => throw new Exception("Unsupported payment provider: {$providerName}"),
        };
    }

    /**
     * بدء عملية دفع جديدة وقيد الحفظ في قاعدة البيانات
     *
     * @param  Model  $payable  الموديل المراد دفعه (Course, Appointment)
     * @param  string  $provider  اسم الشركة (paymob, etc.)
     * @param  float  $amount  المبلغ
     * @param  string  $currency  العملة
     * @return array [redirect_url, payment_record]
     */
    public function createPayment(Model $payable, string $provider, float $amount, string $currency = 'EGP'): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! $user) {
            throw new Exception('User must be authenticated to initiate payment');
        }

        $providerInstance = $this->getProvider($provider);

        // بدء الدفع عند البوابة
        $initiation = $providerInstance->initiate($amount, $currency, [
            'first_name' => explode(' ', $user->name ?? 'Guest')[0],
            'last_name' => explode(' ', $user->name ?? 'User')[1] ?? ($user->name ?? 'User'),
            'email' => $user->email ?? 'test@example.com',
            'phone' => $user->phone ?? '01000000000',
        ]);

        // إنشاء السجل في قاعدة البيانات
        $payment = Payment::create([
            'user_id' => $user->id,
            'payable_id' => $payable->id,
            'payable_type' => get_class($payable),
            'amount' => $amount,
            'currency' => $currency,
            'provider' => $provider,
            'provider_id' => $initiation['provider_id'],
            'status' => 'pending',
        ]);

        return [
            'redirect_url' => $initiation['redirect_url'] ?? null,
            'client_secret' => $initiation['client_secret'] ?? null,
            'payment' => $payment,
        ];
    }

    /**
     * معالجة الدفع الناجح وتحديث النظام
     */
    public function handleSuccessfulPayment(Payment $payment): void
    {
        if ($payment->status === 'success') {
            return;
        }

        DB::transaction(function () use ($payment) {
            $payment->update(['status' => 'success']);

            $payable = $payment->payable;

            // إذا كان المدفوع فاتورة
            if ($payable instanceof \App\Models\Invoice) {
                $payable->update(['status' => 'paid']);
                // سيقوم الـ InvoiceObserver تلقائياً بتحديث المحفظة والسجل المالي
            }

            // إذا كان المدفوع طلباً
            if ($payable instanceof \App\Models\Order) {
                // قد نحتاج لمنطق خاص هنا
                $payable->update(['status' => 'confirmed']);
            }
        });
    }
}
