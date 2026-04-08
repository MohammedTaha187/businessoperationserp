<?php

namespace Database\Seeders\V1;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
            'companies', 'branches', 'users', 'customers',
            'opportunities', 'interaction-notes', 'categories', 'products',
            'inventories', 'inventory-transactions', 'orders', 'order-items',
            'invoices', 'wallets', 'payment-methods', 'financial-records',
            'employees', 'salaries', 'notifications',
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
            'view customers', 'create customers', 'edit customers',
            'view opportunities', 'create opportunities', 'edit opportunities',
            'view products', 'create products', 'edit products',
            'view inventories', 'view inventory-transactions',
            'view orders', 'create orders', 'edit orders',
            'view invoices', 'view employees', 'view financial-records',
        ]);

        // 3. Employee - Basic access
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $employee->givePermissionTo([
            'view customers',
            'view opportunities',
            'view products',
            'view inventories',
            'view orders',
        ]);
    }
}
