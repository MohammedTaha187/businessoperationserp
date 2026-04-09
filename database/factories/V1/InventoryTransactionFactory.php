<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryTransaction>
 */
class InventoryTransactionFactory extends Factory
{
    protected $model = \App\Models\InventoryTransaction::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'user_id' => \App\Models\User::factory(),
            'reference_id' => null,
            'type' => fake()->randomElement(['in', 'out', 'transfer', 'adjustment']),
            'notes' => fake()->sentence(),
        ];
    }
}
