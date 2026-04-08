<?php

namespace App\Http\Services\Payments;

use App\Contracts\PaymentProvider;
use Illuminate\Http\Request;

class KashierProvider implements PaymentProvider
{
    protected string $merchantId;

    protected string $apiKey;

    protected string $mode;

    public function __construct()
    {
        $this->merchantId = env('KASHIER_MERCHANT_ID');
        $this->apiKey = env('KASHIER_API_KEY');
        $this->mode = env('KASHIER_MODE', 'sandbox');
    }

    /**
     * بدء عملية الدفع عبر Kashier (Checksum + Redirect)
     */
    public function initiate(float $amount, string $currency, array $customerDetails): array
    {
        $orderId = uniqid('order_');
        $frontendUrl = env('FRONTEND_URL').'/payment/success'; // الصفحة التي سيعود إليها المستخدم في الفرونت اند
        $path = "/?payment={$this->merchantId}&amount={$amount}&currency={$currency}&orderId={$orderId}&redirectUrl={$frontendUrl}";

        // توليد الـ Hash للأمان (Kashier Checksum)
        $stringToHash = "/?payment={$this->merchantId}&amount={$amount}&currency={$currency}&orderId={$orderId}";
        $hash = hash_hmac('sha256', $stringToHash, $this->apiKey);

        $baseUrl = $this->mode === 'sandbox' ? 'https://checkout.sandbox.kashier.it' : 'https://checkout.kashier.it';
        $redirectUrl = "{$baseUrl}/{$stringToHash}&hash={$hash}";

        return [
            'redirect_url' => $redirectUrl,
            'provider_id' => $orderId,
            'status' => 'pending',
        ];
    }

    /**
     * التحقق من نجاح العملية (Verification) عبر Kashier
     */
    public function verify(Request $request): bool
    {
        $queryString = $request->getQueryString();
        $receivedHash = $request->query('signature');

        // نقوم بإزالة الـ signature من الـ query string لإعادة حساب الـ hash
        $queryStringWithoutSignature = preg_replace('/&signature=[^&]*/', '', $queryString);
        $calculatedHash = hash_hmac('sha256', $queryStringWithoutSignature, $this->apiKey);

        return hash_equals($calculatedHash, $receivedHash) && $request->query('paymentStatus') === 'SUCCESS';
    }
}
