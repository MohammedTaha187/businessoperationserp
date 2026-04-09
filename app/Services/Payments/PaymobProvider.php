<?php

namespace App\Services\Payments;

use App\Contracts\PaymentProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymobProvider implements PaymentProvider
{
    protected string $apiKey;

    protected string $integrationId;

    protected string $iframeId;

    protected string $hmac;

    public function __construct()
    {
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->integrationId = env('PAYMOB_CARD_INTEGRATION_ID');
        $this->iframeId = env('PAYMOB_IFRAME_ID');
        $this->hmac = env('PAYMOB_HMAC');
    }

    /**
     * بدء عملية الدفع عبر Paymob (المصادقة -> الطلب -> كود الدفع)
     */
    public function initiate(float $amount, string $currency, array $customerDetails): array
    {
        // الخطوة 1: الحصول على توكن المصادقة (Auth Token)
        $authResponse = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => $this->apiKey,
        ]);

        if (! $authResponse->successful()) {
            throw new Exception('Paymob Auth Failed');
        }

        $token = $authResponse->json('token');

        // الخطوة 2: إنشاء الطلب (Order ID)
        $orderResponse = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => 'false',
            'amount_cents' => (int) ($amount * 100),
            'currency' => $currency,
            'items' => [],
        ]);

        if (! $orderResponse->successful()) {
            throw new Exception('Paymob Order Creation Failed');
        }

        $orderId = $orderResponse->json('id');

        // الخطوة 3: توليد كود الدفع (Payment Key)
        $paymentKeyResponse = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'auth_token' => $token,
            'amount_cents' => (int) ($amount * 100),
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => [
                'first_name' => $customerDetails['first_name'] ?? 'Guest',
                'last_name' => $customerDetails['last_name'] ?? 'User',
                'email' => $customerDetails['email'] ?? 'test@example.com',
                'phone_number' => $customerDetails['phone'] ?? '01012345678',
                'apartment' => 'NA',
                'floor' => 'NA',
                'street' => 'NA',
                'building' => 'NA',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => 'NA',
                'country' => 'NA',
                'state' => 'NA',
            ],
            'currency' => $currency,
            'integration_id' => $this->integrationId,
        ]);

        if (! $paymentKeyResponse->successful()) {
            throw new Exception('Paymob Payment Key Generation Failed');
        }

        $paymentToken = $paymentKeyResponse->json('token');

        return [
            'redirect_url' => "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}",
            'provider_id' => $orderId,
            'status' => 'pending',
        ];
    }

    /**
     * التحقق من صحة عملية الدفع عبر الـ HMAC
     */
    public function verify(Request $request): bool
    {
        $data = $request->all();
        $hmacArr = [
            $data['amount_cents'],
            $data['created_at'],
            $data['currency'],
            $data['error_occured'],
            $data['has_parent_transaction'],
            $data['id'],
            $data['integration_id'],
            $data['is_3d_secure'],
            $data['is_auth'],
            $data['is_capture'],
            $data['is_refunded'],
            $data['is_standalone_payment'],
            $data['is_voided'],
            $data['order'],
            $data['owner'],
            $data['pending'],
            $data['source_data_pan'],
            $data['source_data_sub_type'],
            $data['source_data_type'],
            $data['success'],
        ];

        $concatenatedString = implode('', $hmacArr);
        $hashedHmac = hash_hmac('sha512', $concatenatedString, $this->hmac);

        return $hashedHmac === $request->query('hmac');
    }
}
