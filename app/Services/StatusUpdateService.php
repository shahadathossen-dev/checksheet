<?php

namespace App\Services;

use App\Enums\CheckSheetType;
use App\Events\DueStatusEvent;
use Carbon\Carbon;

class StatusUpdateService
{
    public static function update($tasklist)
    {
        $taskItems = $tasklist->items;
        $totalCount = $taskItems->count();
        $doneCount = $taskItems->where('done', 1)->count();
        if($doneCount == $totalCount) {
            $tasklist->markAsDone();
        } else if($tasklist->due_date < today()) {
            if($tasklist->type == CheckSheetType::DAILY() || Carbon::parse($tasklist->due_date)->diffInDays(today()) > 1)
            $tasklist->markAsDue();
            DueStatusEvent::dispatch($tasklist->fresh());
        }
    }
}