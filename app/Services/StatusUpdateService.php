<?php

namespace App\Services;

use App\Events\DueStatusEvent;
use App\Jobs\StatusNotificationJob;

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
            DueStatusEvent::dispatch($tasklist->fresh());
        }
    }
}