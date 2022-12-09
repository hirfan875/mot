<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'is_approved' => true,
            'type' => Store::INDIVIDUAL,
            'tax_id' => $this->faker->numerify('###-###-###'),
            'tax_id_type' => 'ssn',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country_id' => Country::first()->id,
            'zipcode' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'status' => true,
        ];
    }
}
