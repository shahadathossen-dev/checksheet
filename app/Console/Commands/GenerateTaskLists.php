<?php

namespace App\Console\Commands;

use App\Enums\CheckSheetType;
use App\Enums\LeaveType;
use App\Models\CheckSheet;
use App\Models\Leave;
use App\Models\TaskList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateTaskLists extends Command
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
    protected $description = 'This command will seed the checksheets for each user.';

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
        User::withoutSuperAdmin()->whereHas('checksheets')->get()->each(function($user) {
            foreach (CheckSheetType::toArray() as $type)
            {
                $tasklistPending = TaskList::where(['type' => $type, 'user_id' => $user->id])->pending()->exists();
                $assignedChecksheet = $checksheet = CheckSheet::where(['type' => $type, 'user_id' => $user->id])->first();

                // Skip if pending tasklist already exists
                if(!$tasklistPending && $assignedChecksheet) {
                    $today = today();
                    
                    if ($checksheet->dueBy != null) {
                        $dueDate = $checksheet->type == CheckSheetType::MONTHLY() ?
                            $today->setDays($checksheet->dueBy) :
                            ($checksheet->type == CheckSheetType::WEEKLY() ?
                            $today->startOfWeek()->addDays($checksheet->dueBy) :
                            $today);
                    } else {
                        $dueDate = $checksheet->type == CheckSheetType::MONTHLY() ? $today->endOfMonth() :
                            ($checksheet->type == CheckSheetType::WEEKLY() ? $today->endOfWeek() :
                            $today);
                    }
                    
                    // Skip weekends and general holidays
                    // $individualLeave = Leave::whereDate('date', $today)->where('type', LeaveType::INDIVIDUAL())->exists();
                    $individualLeave = Leave::where([['start_date', '<=', $today], ['end_date', '>=', $today]])
                        ->where(['user_id' => $user->id, 'type' => LeaveType::INDIVIDUAL()])->exists();

                    if ($type == CheckSheetType::DAILY() && $individualLeave) continue;

                    $tasklist = $user->tasklists()->create([
                        'type'  => $type,
                        'checksheet_id' => $checksheet->id,
                        // 'submitted_by' => $user->id,
                        'due_date'  => Carbon::parse($dueDate)->format('Y-m-d'),
                    ]);

                    $taskItems = $checksheet->checksheetItems->map(fn($item) => ['checksheet_item_id' => $item->id]);

                    $tasklist->items()->createMany($taskItems);
                }
            }
        });
    }
}
