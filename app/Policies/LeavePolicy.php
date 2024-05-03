<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Leave;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-any-leaves');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $model
     * @return mixed
     */
    public function view(User $user, Leave $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-leaves');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $delegate
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create-leaves');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $model
     * @return mixed
     */
    public function update(User $user, Leave $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update-leaves');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $delegate
     * @param  \App\Models\Leave  $model
     * @return mixed
     */
    public function delete(User $user, Leave $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete-leaves');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $mdoel
     * @return mixed
     */
    public function restore(User $user, Leave $mdoel)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore-leaves');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $model
     * @return mixed
     */
    public function forceDelete(User $user, Leave $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force-delete-leaves');
    }
}
