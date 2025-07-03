<?php

namespace App\Policies;

use App\Models\Movement;
use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MovementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Movement $movement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Movement $movement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
     use HandlesAuthorization;

    public function delete(User $user, Movement $movement)
    {
        // Permitir apenas se a movimentação for recente (últimas 24h)
        return $movement->created_at->gt(now()->subDay());
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Movement $movement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Movement $movement): bool
    {
        return false;
    }
}
