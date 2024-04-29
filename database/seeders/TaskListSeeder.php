<?php

namespace Database\Seeders;

use App\Enums\TaskListStatus;
use App\Enums\CheckSheetType;
use App\Enums\TaskListStatus;
use App\Models\CheckSheet;
use App\Models\TaskItem;
use App\Models\TaskList;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Arr;

class TaskListSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $today = Carbon::today();
        // given day of month
        // $dueDate = $today->setDays(30)->format('Y-m-d');
        // $dueDate = $today->startOfWeek()->addDays(6)->format('Y-m-d');
        // last day of month
        // $dueDate = $today->endOfWeek()->format('Y-m-d');
        // $dueDate = $today->endOfMonth()->format('Y-m-d');
        // dd($dueDate);
        
        // $startDate = $today->setDays(30)->yesterday();
        // $endDate = $today->setDays(30)->tomorrow();

        // $period = CarbonPeriod::create($startDate, $endDate)->toArray();
        // dd($period);

        $users = User::withoutSuperAdmin()->get();
        $users->each(function ($user) {
            $user->checksheets->each(function ($checksheet) {
                $today = Carbon::today();
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
                
                $startDate = $dueDate->copy()->subDay();
                $endDate = $dueDate->copy()->addDay();
                $submitDates = CarbonPeriod::create($startDate, $endDate)->toArray();
                $index = array_rand($submitDates);
                $submitDate = $submitDates[$index];

                $status = $submitDate > $dueDate ?
                    TaskListStatus::DUE() :
                    TaskListStatus::DONE();

                $tasklist = TaskList::create([
                        'checksheet_id' => $checksheet->id,
                        'submitted_by' => $checksheet->user_id,
                        'submit_date' => $submitDate->format('Y-m-d'),
                        'due_date' => $dueDate->format('Y-m-d'),
                        'status' => $status,
                    ]);

                $checksheet->checksheetItems->each(function($item) use ($tasklist) {
                    TaskItem::create([
                            'tasklist_id' => $tasklist->id,
                            'checksheet_item_id' => $item->id,
                            'note' => fake()->sentence(3),
                            'done' => $tasklist->status == TaskListStatus::DONE(),
                        ]);
                });
            });

        });

        // TaskList::factory(50)->create();
    }
}
