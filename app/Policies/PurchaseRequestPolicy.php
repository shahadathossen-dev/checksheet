<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseRequestPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-any-purchase-requests');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequest  $model
     * @return mixed
     */
    public function view(User $user, PurchaseRequest $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-purchase-requests');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $delegate
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create-purchase-requests');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequest  $model
     * @return mixed
     */
    public function update(User $user, PurchaseRequest $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update-purchase-requests');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $delegate
     * @param  \App\Models\PurchaseRequest  $model
     * @return mixed
     */
    public function delete(User $user, PurchaseRequest $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete-purchase-requests');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequest  $mdoel
     * @return mixed
     */
    public function restore(User $user, PurchaseRequest $mdoel)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore-purchase-requests');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseRequest  $model
     * @return mixed
     */
    public function forceDelete(User $user, PurchaseRequest $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force-delete-purchase-requests');
    }
}
