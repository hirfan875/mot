<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'is_default' => false,
            'name' => $this->faker->streetName,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'address2' => $this->faker->streetAddress,
            'address3' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'zipcode' => $this->faker->postcode,
            'country' => $this->faker->country,
        ];
    }
}
