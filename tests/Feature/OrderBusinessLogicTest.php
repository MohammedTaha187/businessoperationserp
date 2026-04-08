<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_is_deducted_when_order_is_confirmed()
    {
        // 1. إعداد البيانات الأساسية
        $company = Company::factory()->create();
        $branch = Branch::factory()->create(['company_id' => $company->id]);
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        // 2. إعداد المخزون الابتدائي (100 قطعة)
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'quantity' => 100,
            'min_quantity' => 10,
        ]);

        // 3. إنشاء طلب (Order) بحالة 'pending'
        $order = Order::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'order_number' => 'ORD-001',
            'status' => 'pending',
            'total_amount' => 500,
            'net_amount' => 500,
        ]);

        // 4. إضافة عنصر للطلب (5 قطع)
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unit_price' => 100,
            'subtotal' => 500,
        ]);

        // تأكد أن المخزون لم يتغير بعد
        $this->assertEquals(100, $inventory->fresh()->quantity);

        // 5. تأكيد الطلب (تغيير الحالة)
        $order->update(['status' => 'confirmed']);

        // 6. التحقق من النتيجة: المخزون يجب أن يصبح 95
        $this->assertEquals(95, $inventory->fresh()->quantity);

        // التحقق من وجود سجل في حركة المخازن
        $this->assertDatabaseHas('inventory_transactions', [
            'inventory_id' => $inventory->id,
            'type' => 'out',
            'quantity' => 5,
        ]);
    }
}
