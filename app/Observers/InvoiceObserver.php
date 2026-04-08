<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Notifications\InvoicePaidNotification;

class InvoiceObserver
{
    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        // إذا تغيرت الحالة إلى مدفوع (paid) ولم تكن كذلك من قبل
        if ($invoice->isDirty('status') && $invoice->status === 'paid') {
            app(\App\Http\Services\Api\V1\FinancialService::class)->processInvoicePayment($invoice);

            // إشعار للمسؤول أو المنشئ
            if ($invoice->order->user) {
                $invoice->order->user->notify(new InvoicePaidNotification($invoice));
            }
        }
    }
}
