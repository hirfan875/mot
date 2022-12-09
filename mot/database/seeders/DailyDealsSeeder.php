<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DailyDeal;
use App\Models\OrderItem;
use App\Models\ProductGallery;
use App\Models\ProductReview;
use App\Models\StoreReview;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use App\Models\Product;

class DailyDealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dailyDeals = [
            [
                'product_id' => 1,
                'image' => 'deal1.png',
            ],
            [
                'product_id' => 2,
                'image' => 'deal2.png',
            ],
            [
                'product_id' => 3,
                'image' => 'deal3.png',
            ],
            [
                'product_id' => 4,
                'image' => 'deal4.png',
            ],
        ];
        foreach ($dailyDeals as $dailyDeal) {
            $dailyDeal = DailyDeal::factory([
                'product_id' => $dailyDeal['product_id'],
                'image' => $dailyDeal['image'],
                'discount' => 40
            ])->create();
            $imagePath = '../public/static/assets/img/home_products/';
            $sliderSource = app_path($imagePath) . $dailyDeal['image'];
            $destination = storage_path(config('media.path.original')) . $dailyDeal['image'];
            if (file_exists($sliderSource)) {
                copy($sliderSource, $destination);
            }
        }
    }
}
