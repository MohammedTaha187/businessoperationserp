<?php

namespace App\Http\Services\Api\V1;

use App\Models\FinancialRecord;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\DB;

class FinancialService
{
    /**
     * معالجة دفع الفاتورة وتحديث المحفظة
     */
    public function processInvoicePayment($invoice): void
    {
        DB::transaction(function () use ($invoice) {
            $order = $invoice->order;

            // البحث عن المحفظة الافتراضية للفرع
            $wallet = Wallet::where('branch_id', $order->branch_id)->first();

            if (! $wallet) {
                throw new Exception("No wallet found for branch ID: {$order->branch_id}");
            }

            // إضافة المبلغ لرصيد المحفظة
            $this->incrementWalletBalance($wallet, $invoice->total_amount);

            // تسجيل سجل مالي (Income)
            $this->recordIncome($order->company_id, $wallet->id, $invoice->id, 'Invoice', $invoice->total_amount, "Payment received for Invoice #{$invoice->invoice_number}");
        });
    }

    /**
     * زيادة رصيد المحفظة
     */
    public function incrementWalletBalance(Wallet $wallet, float $amount): bool
    {
        return $wallet->increment('balance', $amount);
    }

    /**
     * تسجيل دخل جديد
     */
    public function recordIncome(int $companyId, int $walletId, int $refId, string $refType, float $amount, string $description): FinancialRecord
    {
        return FinancialRecord::create([
            'company_id' => $companyId,
            'wallet_id' => $walletId,
            'payment_method_id' => 1, // سنفترض 1 كقيمة افتراضية (Cash/Bank)
            'reference_id' => $refId,
            'reference_type' => $refType,
            'type' => 'income',
            'amount' => $amount,
            'description' => $description,
        ]);
    }
}
