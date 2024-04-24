<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TaskItem;
use App\Models\TaskList;
use App\Models\User;

class TaskItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tasklist = $this->faker(TaskList::all());
        return [
            'tasklist_id' => $tasklist->id,
            'checksheet_item_id' => $this->faker->randomElement($tasklist->checksheet->items()->pluck('id')->toArray()->values()),
            'note' => $this->faker->sentence(3),
            'done' => $this->faker->randomElement([0, 1]),
        ];
    }
}
