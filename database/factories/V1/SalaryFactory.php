<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Salary>
 */
class SalaryFactory extends Factory
{
    protected $model = \App\Models\Salary::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $basic = fake()->randomFloat(2, 3000, 15000);
        $bonuses = fake()->randomFloat(2, 0, 2000);
        $deductions = fake()->randomFloat(2, 0, 500);
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'month' => fake()->date('Y-m-01'),
            'year' => 2026,
            'basic' => $basic,
            'bonuses' => $bonuses,
            'deductions' => $deductions,
            'net' => $basic + $bonuses - $deductions,
            'status' => fake()->randomElement(['pending', 'paid']),
        ];
    }
}
