<?php

namespace Database\Factories;

use App\Models\Translation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'language_id' => Language::factory(),
            'status' => true,
            'key' => $this->faker->name,
            'translate' => $this->faker->name,
        ];
    }
}
