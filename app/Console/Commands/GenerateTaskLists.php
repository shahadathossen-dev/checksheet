<?php

namespace App\Console\Commands;

use App\Enums\CheckSheetStatus;
use App\Enums\CheckSheetType;
use App\Models\CheckSheet;
use App\Models\TaskItem;
use App\Models\TaskList;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;

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
                $tasklistExists = TaskList::where(['type' => $type, 'user_id' => $user->id])->pending()->exists();

                if(!$tasklistExists) {
                    $today = today();
                    $checksheet = CheckSheet::where(['type' => $type, 'user_id' => $user->d])->first();
                    
                    if ($checksheet->due_by != null) {
                        $dueDate = $checksheet->type == CheckSheetType::MONTHLY() ?
                            $today->setDays($checksheet->due_by) :
                            ($checksheet->type == CheckSheetType::WEEKLY() ?
                            $today->startOfWeek()->addDays($checksheet->due_by) :
                            $today);
                    } else {
                        $dueDate = $checksheet->type == CheckSheetType::MONTHLY() ? $today->endOfMonth() :
                            ($checksheet->type == CheckSheetType::WEEKLY() ? $today->endOfWeek() :
                            $today);
                    }
                    
                    $tasklist = $user->tasklists()->create([
                        'checksheet_id' => $checksheet->id,
                        'type'  => $type,
                        'due_date'  => Carbon::parse($dueDate)->format('Y-m-d'),
                    ]);


                    // $checksheet->checksheetItems->each(fn($item) => $item->taskItems()->create([
                    //     'tasklist_id' => $tasklist->id
                    // ]));

                    $taskItems = $checksheet->checksheetItems->map(fn($item) => ['checksheet_item_id' => $item->id]);

                    $tasklist->items()->createMany($taskItems);
                }
            }
        });
    }
}
