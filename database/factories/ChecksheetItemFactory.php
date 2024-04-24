<?php

namespace Database\Factories;

use App\Models\CheckSheet;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ChecksheetItem;
use App\Models\User;

class ChecksheetItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChecksheetItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'required' => $this->faker->randomElement([0, 1]),
            'checksheet_id' => $this->faker->randomElement(CheckSheet::pluck('id')->toArray()),
        ];
    }
}
