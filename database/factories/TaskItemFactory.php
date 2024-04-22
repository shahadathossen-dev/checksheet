<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TaskItem;
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
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(100),
            'due_date' => $this->faker->dateTimeThisMonth('+12 days'),
            'user_id' => $this->faker->randomElement(User::withoutSuperAdmin()->pluck('id')->toArray()),
            'created_by' => User::superAdmin()->first()->id,
        ];
    }
}
