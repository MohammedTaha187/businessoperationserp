<?php

namespace App\Contracts;

use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * واجهة برمجية موحدة لكافة بوابات الدفع (Payment Provider Contract)
 */
interface PaymentProvider
{
    /**
     * بدء عملية الدفع والحصول على رابط التوجيه أو التوكن
     *
     * @param  float  $amount  المبلغ
     * @param  string  $currency  العملة
     * @param  array  $customerDetails  بيانات العميل
     * @return array [redirect_url, transaction_id, status]
     */
    public function initiate(float $amount, string $currency, array $customerDetails): array;

    /**
     * التحقق من صحة العملية بعد العودة من البوابة
     *
     * @param  Request  $request  طلب الـ Callback أو الـ Webhook
     */
    public function verify(Request $request): bool;
}
