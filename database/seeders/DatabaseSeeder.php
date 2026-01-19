<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Database\Seeders\V1\RolePermissionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Run Role & Permission Seeder
        $this->call(RolePermissionSeeder::class);

        // 2. Create a Default Company and Branch
        $company = Company::factory()->create([
            'name' => 'Main ERP Company',
        ]);

        $branch = Branch::factory()->create([
            'company_id' => $company->id,
            'name' => 'Main Branch',
        ]);

        // 3. Create a Super Admin User
        $admin = User::factory()->create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'name' => 'Admin User',
            'email' => 'admin@erp.com',
            'password' => bcrypt('password'),
            'role' => 'super-admin',
        ]);

        $admin->assignRole('super-admin');

        // 4. Create some test employees
        User::factory(5)->create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'role' => 'employee',
        ])->each(function ($user) {
            $user->assignRole('employee');
        });
    }
}
