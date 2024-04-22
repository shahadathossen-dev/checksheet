<?php

namespace App\Jobs;

use App\Models\Delegate;
use Illuminate\Bus\Queueable;
use App\Notifications\WelcomeMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;


class NewDelegateRegistered implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $delegate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Delegate $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->delegate->notify(new WelcomeMessage);
    }
}
