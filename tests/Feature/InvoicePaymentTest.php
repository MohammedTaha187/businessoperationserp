<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_balance_is_updated_when_invoice_is_paid()
    {
        // 1. Setup
        $company = Company::factory()->create();
        $branch = Branch::factory()->create(['company_id' => $company->id]);
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        // Wallet for branch
        $wallet = Wallet::factory()->create([
            'branch_id' => $branch->id,
            'company_id' => $company->id,
            'balance' => 1000,
        ]);

        // Order
        $order = Order::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'order_number' => 'ORD-100',
            'status' => 'confirmed',
            'net_amount' => 500,
        ]);

        // Invoice
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-100',
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
            'total_amount' => 500,
            'paid_amount' => 0,
        ]);

        // 2. Action: Mark as Paid
        $invoice->update(['status' => 'paid']);

        // 3. Assertions
        $this->assertEquals(1500, $wallet->fresh()->balance);

        $this->assertDatabaseHas('financial_records', [
            'wallet_id' => $wallet->id,
            'type' => 'income',
            'amount' => 500,
            'reference_id' => $invoice->id,
            'reference_type' => 'Invoice',
        ]);
    }
}
