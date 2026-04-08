<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\OrderConfirmedNotification;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // إذا تغيرت الحالة إلى مؤكد (confirmed) ولم تكن كذلك من قبل
        if ($order->isDirty('status') && $order->status === 'confirmed') {
            app(\App\Http\Services\Api\V1\InventoryService::class)->deductStockForOrder($order);

            // إرسال نوتيفيكيشن للعميل
            if ($order->customer) {
                $order->customer->notify(new OrderConfirmedNotification($order));
            }
        }
    }
}
