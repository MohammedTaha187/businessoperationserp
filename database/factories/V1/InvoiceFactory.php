<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = \App\Models\Invoice::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => \App\Models\Order::factory(),
            'invoice_number' => fake()->unique()->numerify('INV-#####'),
            'status' => fake()->randomElement(['unpaid', 'partial', 'paid']),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'total_amount' => fake()->randomFloat(2, 1000, 50000),
            'paid_amount' => 0,
        ];
    }
}
