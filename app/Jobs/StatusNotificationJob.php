<?php

namespace App\Jobs;

use App\Enums\TaskListStatus;
use App\Models\TaskList;
use App\Models\User;
use App\Notifications\StatusNotificationAdmin;
use App\Notifications\StatusNotificationUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class StatusNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public TaskList $tasklist)
    {
        // dd($tasklist);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $assignee = $this->tasklist->assignee;
        $adminUsers = User::adminUsers()->get();

        if($this->tasklist->status == TaskListStatus::DUE())
        $assignee->notify(new StatusNotificationUser($this->tasklist));
        Notification::send($adminUsers, new StatusNotificationAdmin($this->tasklist));
    }
}
