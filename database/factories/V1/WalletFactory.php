<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    protected $model = \App\Models\Wallet::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'name' => fake()->randomElement(['Safe', 'Cash Register', 'Main Account', 'Petty Cash']),
            'balance' => fake()->randomFloat(2, 0, 100000),
            'currency' => fake()->currencyCode(),
        ];
    }
}
