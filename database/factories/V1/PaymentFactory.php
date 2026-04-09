<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = \App\Models\Payment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'payable_id' => \App\Models\Invoice::factory(),
            'payable_type' => \App\Models\Invoice::class,
            'amount' => fake()->randomFloat(2, 100, 10000),
            'currency' => 'EGP',
            'provider' => fake()->randomElement(['paymob', 'stripe', 'fawry', 'manual']),
            'provider_id' => fake()->unique()->bothify('PAY-####-####'),
            'status' => fake()->randomElement(['pending', 'success', 'failed']),
            'extra_details' => null,
        ];
    }
}
