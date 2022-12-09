<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use function PHPUnit\Framework\fileExists;

class MovePlaceholderToStorageFolder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filename = 'placeholder.jpg';
        $imagePath = '../public/assets/frontend/assets/img/';
        $placeholderSource = app_path($imagePath) . $filename;
        $destination = storage_path(config('media.path.original')) . $filename;
        if (file_exists($placeholderSource)) {
            copy($placeholderSource, $destination);
        }

        update_option('media_placeholder', $filename);



        $sellerfilename = 'seller.png';
        $sellerimagePath = '../public/assets/frontend/assets/img/';
        $sellerSource = app_path($sellerimagePath) . $sellerfilename;
        $sellerdestination = storage_path(config('media.path.original')) . $sellerfilename;
        if (file_exists($sellerSource)) {
            copy($sellerSource, $sellerdestination);
        }

        update_option('media_seller', $sellerfilename);
    }
}
