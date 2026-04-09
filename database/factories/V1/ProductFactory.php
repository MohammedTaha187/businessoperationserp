<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(),
            'category_id' => \App\Models\Category::factory(),
            'name' => fake()->words(3, true),
            'sku' => strtoupper(fake()->unique()->bothify('PROD-####-????')),
            'product_code' => strtoupper(fake()->unique()->bothify('PRD-####')),
            'price' => fake()->randomFloat(2, 100, 10000),
            'cost_price' => fake()->randomFloat(2, 50, 5000),
            'stock_quantity' => fake()->numberBetween(0, 500),
            'status' => fake()->randomElement(['in_stock', 'low_stock', 'out_of_stock']),
            'description' => fake()->sentence(),
            'image' => fake()->imageUrl(),
        ];
    }
}
