<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\DailyDeal;
use App\Models\FlashDeal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class FlashDealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FlashDeal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        static $order = 0;
        return [
            'status' => true,
            'discount' => 10,
            'is_approved' => true,
            'expired' => false,
            'store_id' => 1,
            'product_id' => 1,
            'starting_at' => Carbon::yesterday(),
            'ending_at' => Carbon::today()->addYear(1),
            'image' => '/static/assets/img/home_products/deal1.png',
            'created_by' => 1
        ];
    }
}
