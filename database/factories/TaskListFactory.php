<?php

namespace Database\Factories;

use App\Enums\CheckSheetStatus;
use App\Enums\CheckSheetType;
use App\Enums\TaskListStatus;
use App\Models\CheckSheet;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TaskList;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TaskListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $today = $dueDate = Carbon::today();
        $checksheet = $this->faker->randomElement(CheckSheet::all());
        $period = CarbonPeriod::create('2018-06-14', '2018-06-20');
        if ($checksheet->due_by != null) {
            $dueDate = $checksheet->type == CheckSheetType::MONTHLY() ?
                $today->dayOfMonth($checksheet->due_by)->toDateString() :
                ($checksheet->type == CheckSheetType::WEEKLY() ?
                $today->dayOfWeek($checksheet->due_by)->toDateString() :
                $today->toDateString());
        } else {
            $dueDate = $checksheet->type == CheckSheetType::MONTHLY() ? $today->endOfMonth()->toDateString() : ($checksheet->type == CheckSheetType::WEEKLY() ? $today->endOfWeek()->toDateString() : $today->toDateString());
        }

        $status = $today > $dueDate ? TaskListStatus::DUE() : TaskListStatus::PENDING();
        $submitDate = $this->faker->dateTimeBetween('-1 day', '+1 day');
        
        return [
            'checksheet_id' => $checksheet->id,
            'type' => $checksheet->type,
            'due_date' => $dueDate,
            'user_id' => $checksheet->user_id,
            'submit_date' => $submitDate,
            'submitted_by' => $checksheet->user_id,
            'status' => $status,
        ];

        // return [
        //     'title' => $this->faker->sentence(),
        //     'description' => $this->faker->text(100),
        //     'title' => $this->faker->sentence(),
        //     'user_id' => $this->faker->randomElement(User::withoutSuperAdmin()->pluck('id')->toArray()),
        //     'created_by' => User::superAdmin()->first()->id,
        //     'type' => $type,
        //     'due_by' => $dueBy,
        // ];
    }

}
