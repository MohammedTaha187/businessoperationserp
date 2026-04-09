<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

class InteractionNoteFactory extends Factory
{
    protected $model = \App\Models\InteractionNote::class;

    public function definition(): array
    {
        return [
            'opportunity_id' => \App\Models\Opportunity::factory(),
            'user_id' => \App\Models\User::factory(),
            'note' => fake()->paragraph(),
            'type' => fake()->randomElement(['call', 'email', 'meeting']),
        ];
    }
}
