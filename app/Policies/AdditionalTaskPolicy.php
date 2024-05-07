<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AdditionalTask;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdditionalTaskPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-any-additional-tasks');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdditionalTask  $model
     * @return mixed
     */
    public function view(User $user, AdditionalTask $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-additional-tasks');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $delegate
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create-additional-tasks');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdditionalTask  $model
     * @return mixed
     */
    public function update(User $user, AdditionalTask $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update-additional-tasks');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $delegate
     * @param  \App\Models\AdditionalTask  $model
     * @return mixed
     */
    public function delete(User $user, AdditionalTask $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete-additional-tasks');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdditionalTask  $mdoel
     * @return mixed
     */
    public function restore(User $user, AdditionalTask $mdoel)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore-additional-tasks');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AdditionalTask  $model
     * @return mixed
     */
    public function forceDelete(User $user, AdditionalTask $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force-delete-additional-tasks');
    }
}
