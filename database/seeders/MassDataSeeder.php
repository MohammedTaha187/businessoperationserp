<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\FinancialRecord;
use App\Models\InteractionNote;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Opportunity;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Salary;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MassDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for clean seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Create 3 Companies
        $companies = Company::factory(3)->create();

        foreach ($companies as $company) {
            // 2. Create 2 Branches per Company
            $branches = Branch::factory(2)->create(['company_id' => $company->id]);

            // 3. Create Categories and Products
            $categories = Category::factory(5)->create(['company_id' => $company->id]);
            foreach ($categories as $category) {
                Product::factory(10)->create([
                    'company_id' => $company->id,
                    'category_id' => $category->id,
                ]);
            }

            // 4. Create Payment Methods
            $paymentMethods = PaymentMethod::factory(3)->create(['company_id' => $company->id]);

            foreach ($branches as $branch) {
                // 5. Create Wallets for each branch
                foreach ($paymentMethods as $pm) {
                    Wallet::factory()->create([
                        'company_id' => $company->id,
                        'branch_id' => $branch->id,
                    ]);
                }
                $wallets = Wallet::where('branch_id', $branch->id)->get();

                // 6. Create Users (Employees/Managers)
                $users = User::factory(5)->create([
                    'company_id' => $company->id,
                    'branch_id' => $branch->id,
                ]);

                // 7. Create Employees for these users
                foreach ($users as $user) {
                    $employee = Employee::factory()->create([
                        'user_id' => $user->id,
                        'branch_id' => $branch->id,
                    ]);

                    // Generate last 3 months of salaries
                    for ($m = 1; $m <= 3; $m++) {
                        Salary::factory()->create([
                            'employee_id' => $employee->id,
                            'month' => date('Y-m-01', strtotime("-$m month")),
                            'year' => date('Y'),
                            'status' => 'paid',
                        ]);
                    }
                }

                // 8. Create Customers
                $customers = Customer::factory(10)->create([
                    'company_id' => $company->id,
                    'branch_id' => $branch->id,
                ]);

                foreach ($customers as $customer) {
                    // 9. CRM: Opportunities and Notes
                    $opportunity = Opportunity::factory()->create([
                        'customer_id' => $customer->id,
                        'assigned_to' => $users->random()->id,
                    ]);

                    InteractionNote::factory(3)->create([
                        'opportunity_id' => $opportunity->id,
                        'user_id' => $users->random()->id,
                    ]);

                    // 10. Operations: Orders and Items
                    $orders = Order::factory(3)->create([
                        'company_id' => $company->id,
                        'branch_id' => $branch->id,
                        'customer_id' => $customer->id,
                        'user_id' => $users->random()->id,
                    ]);

                    foreach ($orders as $order) {
                        $products = Product::where('company_id', $company->id)->get()->random(3);
                        $total = 0;
                        foreach ($products as $product) {
                            $item = OrderItem::factory()->create([
                                'order_id' => $order->id,
                                'product_id' => $product->id,
                                'unit_price' => $product->price,
                                'subtotal' => $product->price * 2,
                            ]);
                            $total += $item->subtotal;
                        }
                        $order->update(['total_amount' => $total, 'net_amount' => $total]);

                        // 11. Finance: Invoices and Payments
                        $invoice = Invoice::factory()->create([
                            'order_id' => $order->id,
                            'total_amount' => $total,
                            'paid_amount' => $total,
                            'status' => 'paid',
                        ]);

                        Payment::factory()->create([
                            'user_id' => $users->random()->id,
                            'payable_id' => $invoice->id,
                            'payable_type' => Invoice::class,
                            'amount' => $total,
                            'status' => 'success',
                        ]);

                        // 12. Financial Records
                        FinancialRecord::factory()->create([
                            'company_id' => $company->id,
                            'wallet_id' => $wallets->random()->id,
                            'payment_method_id' => $paymentMethods->random()->id,
                            'reference_id' => $order->id,
                            'reference_type' => 'Order',
                            'type' => 'income',
                            'amount' => $total,
                        ]);
                    }
                }

                // 13. Inventory Management
                $branchProducts = Product::where('company_id', $company->id)->get();
                foreach ($branchProducts as $product) {
                    $inventory = Inventory::factory()->create([
                        'product_id' => $product->id,
                        'branch_id' => $branch->id,
                    ]);

                    InventoryTransaction::factory(5)->create([
                        'product_id' => $product->id,
                        'branch_id' => $branch->id,
                        'user_id' => $users->random()->id,
                        'reference_id' => $inventory->id,
                    ]);
                }

                // 14. Notifications
                foreach ($users as $user) {
                    Notification::factory(10)->create(['user_id' => $user->id]);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Mass seeding completed successfully!');
    }
}
