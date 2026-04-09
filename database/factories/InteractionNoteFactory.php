<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interaction_note>
 */
class InteractionNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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
