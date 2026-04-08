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
use App\Notifications\StockWarningNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StockWarningTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_is_sent_when_stock_is_low()
    {
        Notification::fake();

        // 1. Setup
        $company = Company::factory()->create();
        $manager = User::factory()->create();
        $branch = Branch::factory()->create([
            'company_id' => $company->id,
            'manager_id' => $manager->id,
        ]);
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        // Stock: 15, Min: 10
        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'branch_id' => $branch->id,
            'quantity' => 15,
            'min_quantity' => 10,
        ]);

        // Order
        $order = Order::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'order_number' => 'ORD-LOW',
            'status' => 'pending',
            'net_amount' => 600,
        ]);

        // Order 6 items (Stock will become 9, which is <= 10)
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 6,
            'unit_price' => 100,
            'subtotal' => 600,
        ]);

        // 2. Action: Confirm Order
        $order->update(['status' => 'confirmed']);

        // 3. Assertions
        Notification::assertSentTo(
            $manager,
            StockWarningNotification::class
        );
    }
}
