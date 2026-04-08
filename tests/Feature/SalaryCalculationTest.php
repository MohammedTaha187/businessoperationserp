<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalaryCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_salary_net_is_calculated_automatically()
    {
        // 1. Setup
        $employee = Employee::factory()->create();

        // 2. Action: Create salary
        $salary = Salary::create([
            'employee_id' => $employee->id,
            'month' => '2026-04-01',
            'year' => 2026,
            'basic' => 5000,
            'bonuses' => 1000,
            'deductions' => 500,
            'status' => 'pending',
        ]);

        // 3. Assertions
        // Net should be 5000 + 1000 - 500 = 5500
        $this->assertEquals(5500, $salary->fresh()->net);
    }
}
