<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskList;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskListPolicy
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
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-any-task-lists');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskList  $model
     * @return mixed
     */
    public function view(User $user, TaskList $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('view-task-lists');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $delegate
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('create-task-lists');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskList  $model
     * @return mixed
     */
    public function update(User $user, TaskList $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('update-task-lists');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $delegate
     * @param  \App\Models\TaskList  $model
     * @return mixed
     */
    public function delete(User $user, TaskList $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('delete-task-lists');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskList  $mdoel
     * @return mixed
     */
    public function restore(User $user, TaskList $mdoel)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('restore-task-lists');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TaskList  $model
     * @return mixed
     */
    public function forceDelete(User $user, TaskList $model)
    {
        return $user->isSuperAdmin() || $user->hasPermissionTo('force-delete-task-lists');
    }
}
