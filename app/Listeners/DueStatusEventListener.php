<?php

namespace App\Listeners;

use App\Events\DueStatusEvent;
use App\Jobs\StatusNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DueStatusEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DueStatusEvent $event)
    {
        StatusNotificationJob::dispatch($event->tasklist);
    }
}
