<?php

namespace App\Policies;

use App\Models\InventoryTransaction;
use App\Models\User;

class InventoryTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view inventory-transactions');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->hasPermissionTo('view inventory-transactions');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create inventory-transactions');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->hasPermissionTo('edit inventory-transactions');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->hasPermissionTo('delete inventory-transactions');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->hasPermissionTo('delete inventory-transactions');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InventoryTransaction $inventoryTransaction): bool
    {
        return $user->hasPermissionTo('delete inventory-transactions');
    }
}
