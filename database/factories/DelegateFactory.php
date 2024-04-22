<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Delegate;
use Illuminate\Database\Eloquent\Factories\Factory;

class DelegateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Delegate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => bcrypt(111111),
            'country_id' => $this->faker->randomElement(Country::pluck('id')->toArray()),
        ];
    }
}
