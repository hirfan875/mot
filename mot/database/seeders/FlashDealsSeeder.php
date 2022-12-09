<?php

namespace Database\Seeders;

use App\Models\FlashDeal;
use Illuminate\Database\Seeder;

class FlashDealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $flashDeals = [
            [
                'product_id' => 10,
                'image' => 'deal1.png',
            ],
            [
                'product_id' => 11,
                'image' => 'deal2.png',
            ],
            [
                'product_id' => 12,
                'image' => 'deal3.png',
            ],
            [
                'product_id' => 13,
                'image' => 'deal4.png',
            ],
        ];
        foreach ($flashDeals as $flashDeal) {
            $flashDeal = FlashDeal::factory([
                'product_id' => $flashDeal['product_id'],
                'image' => $flashDeal['image'],
                'discount' => 40
            ])->create();
            $imagePath = '../public/static/assets/img/home_products/';
            $sliderSource = app_path($imagePath) . $flashDeal['image'];
            $destination = storage_path(config('media.path.original')) . $flashDeal['image'];
            if (file_exists($sliderSource)) {
                copy($sliderSource, $destination);
            }
        }
    }
}
