<?php

namespace App\Services;

use App\Events\TaskListDueEvent;

class StatusUpdateService
{
    public static function handle($tasklist)
    {
        $taskItems = $tasklist->items;
        $totalCount = $taskItems->count();
        $doneCount = $taskItems->where('done', 1)->count();
        if($doneCount == $totalCount) {
            $tasklist->markAsDone();
        } else if(today() > $tasklist->dueDate) {
            $tasklist->markAsDue();
            new TaskListDueEvent($tasklist->fresh());
        }
    }
}