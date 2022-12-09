<?php

namespace Database\Factories;

use App\Models\StoreStaff;
use App\Models\Store;
use App\Models\StoreData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StoreStaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StoreStaff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'store_id' => Store::factory()->has(StoreData::factory()->count(1), 'store_data')->create(),
            'is_owner' => false,
        ];
    }
}
