<?php

namespace Database\Seeders;

use App\Models\ProductBanner;
use Illuminate\Database\Seeder;

class ProductBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banner_1 = 'productBanner1.jpg';
        $banner_2 = 'productBanner2.jpg';
        $bannerPath = 'assets/frontend/assets/img/';

        // copy banner 1
        $bannerSource = public_path($bannerPath) . $banner_1;
        $destination = storage_path(config('media.path.original')) . $banner_1;
        if (file_exists($bannerSource)) {
            copy($bannerSource, $destination);
        }

        // copy banner 2
        $bannerSource = public_path($bannerPath) . $banner_2;
        $destination = storage_path(config('media.path.original')) . $banner_2;
        if (file_exists($bannerSource)) {
            copy($bannerSource, $destination);
        }

        // create default banner
        ProductBanner::create([
            'is_default' => true,
            'banner_1' => $banner_1,
            'banner_1_url' => '#',
            'banner_2' => $banner_2,
            'banner_2_url' => '#'
        ]);
    }
}
