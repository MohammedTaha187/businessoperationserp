<?php

namespace App\Policies;

use App\Models\InteractionNote;
use App\Models\User;

class InteractionNotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view interaction-notes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InteractionNote $interactionNote): bool
    {
        return $user->hasPermissionTo('view interaction-notes');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create interaction-notes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InteractionNote $interactionNote): bool
    {
        return $user->hasPermissionTo('edit interaction-notes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InteractionNote $interactionNote): bool
    {
        return $user->hasPermissionTo('delete interaction-notes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InteractionNote $interactionNote): bool
    {
        return $user->hasPermissionTo('delete interaction-notes');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InteractionNote $interactionNote): bool
    {
        return $user->hasPermissionTo('delete interaction-notes');
    }
}
