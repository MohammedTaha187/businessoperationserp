<?php

namespace App\Services\Api\V1;

use App\Models\Order;
use Exception;

class OrderService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * تأكيد الطلب
     */
    public function confirmOrder(Order $order): Order
    {
        if ($order->status === 'confirmed') {
            throw new Exception('Order is already confirmed.');
        }

        // خصم المخزون
        $this->inventoryService->deductStockForOrder($order);

        // تحديث الحالة
        $order->update(['status' => 'confirmed']);

        return $order->fresh();
    }

    /**
     * إلغاء الطلب (مستقبلاً قد يحتاج إعادة المخزون)
     */
    public function cancelOrder(Order $order): Order
    {
        if ($order->status === 'cancelled') {
            throw new Exception('Order is already cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return $order;
    }
}
