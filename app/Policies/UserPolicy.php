<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, User $model): bool
    {
        return $user && ($user->is($model) || $user->can('edit other users'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user && ($user->is($model) || $user->can('delete other users'));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $this->delete($user, $model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user && ($user->is($model) || $user->can('delete other users'));
    }

    /**
     * Determine whether the user can promote/demote the model.
     */
    public function changeRole(User $user, User $model, $next_role = null, $should_change = false): bool
    {
        $next_role = User::sanitizeRole($next_role) ?? $model->next_role_name;

        return
            $user->isNot($model)
            && $user->can("make user {$model->role_name}")
            && $user->can("make user {$next_role}")
            && ($next_role != $model->role_name || !$should_change);
    }

    public function impersonate(User $user, User $model): bool
    {
        return $user->canImpersonate() && $model->canBeImpersonated() && $user->can('impersonate users') && $user->isNot($model) && !$model->can('prevent from impersonation by users') && $user->can("make user {$model->role_name}");
    }
}
