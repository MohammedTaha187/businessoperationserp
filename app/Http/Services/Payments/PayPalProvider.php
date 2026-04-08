<?php

namespace App\Http\Services\Payments;

use App\Contracts\PaymentProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayPalProvider implements PaymentProvider
{
    protected string $clientId;

    protected string $clientSecret;

    protected string $baseUrl;

    public function __construct()
    {
        $this->clientId = env('PAYPAL_SANDBOX_CLIENT_ID');
        $this->clientSecret = env('PAYPAL_SANDBOX_CLIENT_SECRET');
        $this->baseUrl = env('PAYPAL_MODE') === 'sandbox' ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
    }

    /**
     * الحصول على توكن الوصول (Access Token)
     */
    private function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            throw new Exception('PayPal Auth Failed: '.$response->body());
        }

        return $response->json('access_token');
    }

    /**
     * بدء عملية الدفع عبر PayPal (إنشاء طلب خارجي)
     */
    public function initiate(float $amount, string $currency, array $customerDetails): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency === 'EGP' ? 'USD' : $currency, // بايبال لا يدعم الجنيه المصري غالباً
                            'value' => number_format($amount, 2, '.', ''),
                        ],
                    ],
                ],
                'application_context' => [
                    'return_url' => env('FRONTEND_URL').'/payment/success', // العودة للفرونت اند في حالة النجاح
                    'cancel_url' => env('FRONTEND_URL').'/payment/failed',  // العودة للفرونت اند في حالة الإلغاء
                ],
            ]);

        if (! $response->successful()) {
            throw new Exception('PayPal Order Creation Failed: '.$response->body());
        }

        $orderId = $response->json('id');
        $links = $response->json('links');
        $approveLink = collect($links)->firstWhere('rel', 'approve')['href'];

        return [
            'redirect_url' => $approveLink,
            'provider_id' => $orderId,
            'status' => 'pending',
        ];
    }

    /**
     * التحقق من نجاح العملية (Capture)
     */
    public function verify(Request $request): bool
    {
        $orderId = $request->query('token') ?? $request->input('order_id');
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

        return $response->successful() && $response->json('status') === 'COMPLETED';
    }
}
