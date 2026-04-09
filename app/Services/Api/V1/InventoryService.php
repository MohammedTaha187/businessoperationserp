<?php

namespace App\Services\Api\V1;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Notifications\StockWarningNotification;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * خصم المخزون لجميع أصناف الطلب
     */
    public function deductStockForOrder($order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $inventory = $this->getInventoryForProductAtBranch($item->product_id, $order->branch_id);

                if (! $inventory || $inventory->quantity < $item->quantity) {
                    throw new Exception("Inadequate stock for product: {$item->product->name} (ID: {$item->product_id})");
                }

                // خصم الكمية
                $inventory->decrement('quantity', $item->quantity);

                // التحقق من انخفاض المخزون وإرسال تنبيه
                $this->checkLowStock($inventory, $item->product->name);

                // تسجيل الحركة
                $this->recordTransaction($inventory->id, $order->user_id, 'out', $item->quantity, "Deducted for Order #{$order->order_number}");
            }
        });
    }

    /**
     * الحصول على سجل المخزون لمنتج في فرع معين
     */
    public function getInventoryForProductAtBranch(int $productId, int $branchId): ?Inventory
    {
        return Inventory::where('product_id', $productId)
            ->where('branch_id', $branchId)
            ->first();
    }

    /**
     * التحقق من الحد الأدنى وإرسال التنبيه
     */
    protected function checkLowStock(Inventory $inventory, string $productName): void
    {
        if ($inventory->quantity <= $inventory->min_quantity) {
            $manager = $inventory->branch->manager;
            if ($manager) {
                $manager->notify(new StockWarningNotification($productName, $inventory->quantity));
            }
        }
    }

    /**
     * تسجيل حركة مخزنية
     */
    public function recordTransaction(int $inventoryId, int $userId, string $type, int $quantity, string $notes): InventoryTransaction
    {
        return InventoryTransaction::create([
            'inventory_id' => $inventoryId,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $quantity,
            'notes' => $notes,
        ]);
    }
}
