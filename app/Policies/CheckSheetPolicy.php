<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CheckSheet;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckSheetPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-any-checksheets');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckSheet  $model
     * @return mixed
     */
    public function view(User $user, CheckSheet $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-checksheets');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $delegate
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create-checksheets');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckSheet  $model
     * @return mixed
     */
    public function update(User $user, CheckSheet $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update-checksheets');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $delegate
     * @param  \App\Models\CheckSheet  $model
     * @return mixed
     */
    public function delete(User $user, CheckSheet $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete-checksheets');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckSheet  $mdoel
     * @return mixed
     */
    public function restore(User $user, CheckSheet $mdoel)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore-checksheets');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CheckSheet  $model
     * @return mixed
     */
    public function forceDelete(User $user, CheckSheet $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force-delete-checksheets');
    }
}
