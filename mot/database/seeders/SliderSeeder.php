<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sliders = [
            [
                'image' => 'slider1.png',
                'button_text' => 'Shoes',
                'button_url' => '#'
            ],
            [
                'image' => 'slider2.png',
                'button_text' => 'Cosmetics',
                'button_url' => '#'
            ],
            [
                'image' => 'slider3.png',
                'button_text' => 'More Cosmetics',
                'button_url' => '#'
            ],
            [
                'image' => 'slider4.png',
                'button_text' => 'Dreamy',
                'button_url' => '#'
            ],
            [
                'image' => 'slider5.png',
                'button_text' => 'Blue',
                'button_url' => '#'
            ],
            [
                'image' => 'slider6.png',
                'button_text' => 'Side Pose',
                'button_url' => '#'
            ],
            [
                'image' => 'slider7.png',
                'button_text' => 'Yellow',
                'button_url' => '#'
            ],
            [
                'image' => 'slider8.png',
                'button_text' => 'Brown',
                'button_url' => '#',
            ],
        ];
        $count = 0;
        $sliderImagesPath = '../public/assets/frontend/assets/img/slider/';
        foreach ($sliders as $slider) {
            $sliderSource = app_path($sliderImagesPath) . $slider['image'];
            $destination = storage_path(config('media.path.original')) . $slider['image'];
            if (file_exists($sliderSource)) {
                copy($sliderSource, $destination);
            }
            $slider['sort_order'] = $count++;
            Slider::factory($slider)->create($slider);
        }
    }
}
