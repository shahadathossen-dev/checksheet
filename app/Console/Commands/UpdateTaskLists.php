<?php

namespace App\Console\Commands;

use App\Enums\CheckSheetType;
use App\Enums\LeaveType;
use App\Models\Leave;
use App\Models\TaskList;
use App\Services\StatusUpdateService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateTaskLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tasklists';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the tasklists to db.';

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
            ->each(function($tasklist) {
                // Allow 1 day extra as grace period for WEEKLY and MONTHLY checksheets
                if($tasklist->type == CheckSheetType::DAILY() || Carbon::parse($tasklist->dueDate)->diffInDays(today()) > 1)
                StatusUpdateService::update($tasklist);
            });
    }
}
