<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

class OpportunityFactory extends Factory
{
    protected $model = \App\Models\Opportunity::class;

    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'assigned_to' => \App\Models\User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['new', 'negotiation', 'won', 'lost']),
        ];
    }
}
