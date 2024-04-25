<?php

namespace Database\Factories;

use App\Enums\CheckSheetType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CheckSheet;
use App\Models\User;

class CheckSheetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CheckSheet::class;
    protected $type;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->type = $this->faker->randomElement(CheckSheetType::toArray());
        $dueBy = $this->type == CheckSheetType::MONTHLY() ? 30 : ($this->type == CheckSheetType::WEEKLY() ? 6 : 0);
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(100),
            'user_id' => $this->faker->randomElement(User::withoutSuperAdmin()->pluck('id')->toArray()),
            'type' => $this->type,
            'due_by' => $dueBy,
            'created_by' => User::superAdmin()->first()->id,
        ];
    }


    /**
     * Configure the model factory.
     *
     * @return $this
     */
    // public function configure()
    // {
    //     return $this->afterMaking(function (CheckSheet $model) {
    //         //
    //     })->afterCreating(function (CheckSheet $model) {
    //         $model
    //     });
    // }
}
