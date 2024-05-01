<?php

namespace App\Jobs;

use App\Models\TaskList;
use App\Notifications\StatusNotificationAdmin;
use App\Notifications\StatusNotificationUser;
use Illuminate\Bus\Queueable;
use App\Notifications\WelcomeMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;


class StatusNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private $tasklist)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assignee = $this->tasklist->assignee;
        $author = $this->tasklist->author;

        $assignee->notify(new StatusNotificationUser($this->tasklist));
        $author->notify(new StatusNotificationAdmin($this->tasklist));
    }
}
