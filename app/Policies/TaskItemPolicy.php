<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskItemPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-any-task-items');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskItem  $model
     * @return mixed
     */
    public function view(User $user, TaskItem $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-task-items');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create-task-items');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskItem  $model
     * @return mixed
     */
    public function update(User $user, TaskItem $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update-task-items');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskItem  $model
     * @return mixed
     */
    public function delete(User $user, TaskItem $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete-task-items');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskItem  $mdoel
     * @return mixed
     */
    public function restore(User $user, TaskItem $mdoel)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore-task-items');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskItem  $model
     * @return mixed
     */
    public function forceDelete(User $user, TaskItem $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force-delete-task-items');
    }
}
