<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = \App\Models\Inventory::class;

    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'quantity' => fake()->numberBetween(0, 500),
            'min_quantity' => fake()->numberBetween(5, 20),
            'max_quantity' => fake()->numberBetween(100, 1000),
        ];
    }
}
