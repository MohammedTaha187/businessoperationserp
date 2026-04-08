<?php

namespace App\Policies;

use App\Models\Salary;
use App\Models\User;

class SalaryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view salaries');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Salary $salary): bool
    {
        return $user->hasPermissionTo('view salaries');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create salaries');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Salary $salary): bool
    {
        return $user->hasPermissionTo('edit salaries');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Salary $salary): bool
    {
        return $user->hasPermissionTo('delete salaries');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Salary $salary): bool
    {
        return $user->hasPermissionTo('delete salaries');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Salary $salary): bool
    {
        return $user->hasPermissionTo('delete salaries');
    }
}
