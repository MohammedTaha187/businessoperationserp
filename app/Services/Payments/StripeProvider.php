<?php

namespace App\Services\Payments;

use App\Contracts\PaymentProvider;
use Exception;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeProvider implements PaymentProvider
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * بدء عملية الدفع عبر Stripe (إنشاء PaymentIntent)
     */
    public function initiate(float $amount, string $currency, array $customerDetails): array
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($amount * 100), // القروش
                'currency' => strtolower($currency),
                'payment_method_types' => ['card'],
                'metadata' => [
                    'email' => $customerDetails['email'] ?? 'test@example.com',
                ],
            ]);

            return [
                'success_url' => env('FRONTEND_URL').'/payment/success?session_id={CHECKOUT_SESSION_ID}', // العودة للفرونت اند عند النجاح
                'cancel_url' => env('FRONTEND_URL').'/payment/failed', // العودة للفرونت اند عند الإلغاء
                'provider_id' => $paymentIntent->id,
                'status' => 'pending',
            ];
        } catch (Exception $e) {
            throw new Exception('Stripe Payment Initiation Failed: '.$e->getMessage());
        }
    }

    /**
     * التحقق من صحة عملية الدفع عبر Stripe Webhook أو API
     */
    public function verify(Request $request): bool
    {
        $id = $request->input('id');
        $intent = PaymentIntent::retrieve($id);

        return $intent->status === 'succeeded';
    }
}
