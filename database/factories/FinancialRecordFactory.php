<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Financial_record>
 */
class FinancialRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'wallet_id' => \App\Models\Wallet::factory(),
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'reference_id' => fake()->numberBetween(1, 1000),
            'reference_type' => fake()->randomElement(['Order', 'Expense', 'Salary']),
            'type' => fake()->randomElement(['income', 'expense']),
            'amount' => fake()->randomFloat(2, 100, 50000),
            'description' => fake()->sentence(),
        ];
    }
}
