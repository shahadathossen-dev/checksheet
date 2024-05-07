<?php

namespace App\Observers;

use App\Http\Resources\ApiResource;
use App\Models\TaskList;
use App\Jobs\StatusUpdateJob;
use App\Services\StatusUpdateService;

class TaskListObserver
{
    /**
     * Handle the TaskList "created" event.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return void
     */
    public function created(TaskList $tasklist)
    {
        // StatusUpdateService::handle($tasklist);
    }

    /**
     * Handle the TaskList "updated" event.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return void
     */
    public function updated(TaskList $tasklist)
    {
        // StatusUpdateService::handle($tasklist);
    }

    /**
     * Handle the TaskList "deleted" event.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return void
     */
    public function deleted(TaskList $tasklist)
    {
        //
    }

    /**
     * Handle the TaskList "restored" event.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return void
     */
    public function restored(TaskList $tasklist)
    {
        //
    }

    /**
     * Handle the TaskList "force deleted" event.
     *
     * @param  \App\Models\TaskList  $tasklist
     * @return void
     */
    public function forceDeleted(TaskList $tasklist)
    {
        //
    }
}
