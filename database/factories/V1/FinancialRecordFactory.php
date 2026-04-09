<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialRecordFactory extends Factory
{
    protected $model = \App\Models\FinancialRecord::class;

    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'wallet_id' => \App\Models\Wallet::factory(),
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'reference_no' => strtoupper(fake()->bothify('JRNL-####-????')),
            'reference_id' => fake()->numberBetween(1, 1000),
            'reference_type' => fake()->randomElement(['Order', 'Expense', 'Salary']),
            'type' => fake()->randomElement(['income', 'expense']),
            'amount' => fake()->randomFloat(2, 100, 50000),
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']),
            'description' => fake()->sentence(),
        ];
    }
}
