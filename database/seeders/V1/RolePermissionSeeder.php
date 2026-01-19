<?php

namespace Database\Seeders\V1;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Modules and Permissions
        $modules = [
            'companies',
            'branches',
            'users',
            'customers',
            'leads',
            'categories',
            'products',
            'inventory',
            'sales',
            'invoices',
            'payments',
            'wallets',
            'employees',
            'payrolls'
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        // Create Roles and Assign Permissions

        // 1. Super Admin - Has All
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Manager - High level access
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo([
            'view customers',
            'create customers',
            'edit customers',
            'view leads',
            'create leads',
            'edit leads',
            'view products',
            'create products',
            'edit products',
            'view inventory',
            'view sales',
            'view invoices',
            'view employees'
        ]);

        // 3. Employee - Basic access
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $employee->givePermissionTo([
            'view customers',
            'view leads',
            'view products',
            'view inventory'
        ]);
    }
}
