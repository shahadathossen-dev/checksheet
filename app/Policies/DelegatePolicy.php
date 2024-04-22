<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Delegate;
use Illuminate\Auth\Access\HandlesAuthorization;

class DelegatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-any-delegates');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Delegate  $model
     * @return mixed
     */
    public function view(User $user, Delegate $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-delegates');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $delegate
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create-delegates');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Delegate  $model
     * @return mixed
     */
    public function update(User $user, Delegate $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update-delegates');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $delegate
     * @param  \App\Models\Delegate  $model
     * @return mixed
     */
    public function delete(User $user, Delegate $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete-delegates');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Delegate  $mdoel
     * @return mixed
     */
    public function restore(User $user, Delegate $mdoel)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore-delegates');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Delegate  $model
     * @return mixed
     */
    public function forceDelete(User $user, Delegate $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force-delete-delegates');
    }
}
