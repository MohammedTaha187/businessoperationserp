<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Database\Seeders\V1\RolePermissionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        // 2. Run Comprehensive Mass Seeder
        $this->call(MassDataSeeder::class);

        // 3. Create a Default Company and Branch (Optional, for demo/dev access)
        $company = Company::where('name', 'Main ERP Company')->first();
        if (!$company) {
            $company = Company::factory()->create(['name' => 'Main ERP Company']);
        }

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
            'type' => 'super-admin',
        ]);

        $admin->assignRole('super-admin');

        // 4. Create some test employees
        User::factory(5)->create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'type' => 'employee',
        ])->each(function ($user) {
            $user->assignRole('employee');
        });
    }
}
