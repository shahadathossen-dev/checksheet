<?php

namespace App\Console\Commands;

use App\Models\TaskList;
use App\Services\StatusUpdateService;
use Illuminate\Console\Command;

class UpdateTaskLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:tasklists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will seed the permissions to db.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = today();
        TaskList::pending()->whereDate('due_date', '<', $today)->get()
            ->each(fn($tasklist) => StatusUpdateService::update($tasklist));
        
        if(!in_array($today->dayOfWeek, [0, 6]))
            $this->call('generate-tasklists');
    }
}
