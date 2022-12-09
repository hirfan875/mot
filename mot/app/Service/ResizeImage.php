<?php

namespace App\Service;

use Illuminate\Support\Facades\Storage;
use Image;

class ResizeImage
{
    /**
     * resize image
     *
     * @param string $image_name
     * @param string $media_type
     * @return string
     */
    public function resize(string $image_name, string $media_type): string
    {

        try {
            $logger = getLogger('upload-image');
//            $logger->debug('Upload Image : ' .$image_name , [$media_type]);
            
            // ? this is for admin side
            $this->generateThumbnail($image_name);
            
            if ($media_type === 'product') {
                $this->generateProductSizes($image_name);
                return "success";
            }
            
           if ($media_type === 'slider') {
                $this->generateSliderSizes($image_name);
                return "success";
            }
            
            if ($media_type === 'deal') {
                $this->generateDealSizes($image_name);
                return "success";
            }
            
            $this->generateDefaultSizes($image_name);
            return "success";
            
        } catch (\Throwable $e) {
            return __('Invalid image format.');
        }
    }

    /**
     * generate thumbnail
     *
     * @param string $image_name
     * @return void
     */
    protected function generateThumbnail(string $image_name)
    {
        $width = config('media.thumbnail.width');
        $height = config('media.thumbnail.height');
        $this->createDirectory('thumbnail');
        
        $path = storage_path() . config('media.path.thumbnail');
        $originalImage = storage_path() . config('media.path.original') . $image_name;
        
        $img = Image::make($originalImage);
        $img_width = $img->width();
        $img_height = $img->height();
        
        // check new image size
        $needToResize = $img_width > $width || $img_height > $height;
        if (!$needToResize) {
            return;
        }
        
        // resize image
        $img->fit($width, $height)->save($path . $image_name, 100);
    }

    /**
     * generate product sizes
     *
     * @param string $image_name
     * @return void
     */
    protected function generateProductSizes(string $image_name)
    {
        $sizes = config('media.sizes.product');
        $logger = getLogger('upload-image');
//        $logger->debug('Creating Product Image with sizes : ' .$image_name , $sizes);
        foreach ($sizes as $size) {
            $this->generateSize($image_name, $size);
        }
    }

    /**
     * generate deal sizes
     *
     * @param string $image_name
     * @return void
     */
    protected function generateDealSizes(string $image_name)
    {
        $sizes = config('media.sizes.deal');
        $store_path ='';
        $base_path_deal_home = public_path('storage/deal_home/');
        if (!file_exists($base_path_deal_home . $store_path)) {
            \File::makeDirectory($base_path_deal_home . $store_path, 0777, true);
        }
        $base_path_deal_mobile = public_path('storage/deal_mobile/');
        if (!file_exists($base_path_deal_mobile . $store_path)) {
            \File::makeDirectory($base_path_deal_mobile . $store_path, 0777, true);
        }
        foreach ($sizes as $size) {
            $this->generateSize($image_name, $size);
        }
    }

    /**
     * generate sponsor category sizes
     *
     * @param string $image_name
     * @return void
     */
    protected function generateSponsorCategorySizes(string $image_name)
    {
        $sizes = config('media.sizes.sponsor_category');
        $store_path ='';
        $base_path_sponsor_category = public_path('storage/sponsor_category/');
        if (!file_exists($base_path_sponsor_category . $store_path)) {
            \File::makeDirectory($base_path_sponsor_category . $store_path, 0777, true);
        }
        $base_path_sponsor_category_mobile = public_path('storage/sponsor_category_mobile/');
        if (!file_exists($base_path_sponsor_category_mobile . $store_path)) {
            \File::makeDirectory($base_path_sponsor_category_mobile . $store_path, 0777, true);
        }
        foreach ($sizes as $size) {
            $this->generateSize($image_name, $size);
        }
    }
    
    /**
     * generate slider sizes
     *
     * @param string $image_name
     * @return void
     */
    protected function generateSliderSizes(string $image_name)
    {
        $sizes = config('media.sizes.slider');
        $store_path ='';
        $base_path_slider = public_path('storage/slider/');
        if (!file_exists($base_path_slider . $store_path)) {
            \File::makeDirectory($base_path_slider . $store_path, 0777, true);
        }
        $base_path_slider_mobile = public_path('storage/slider_mobile/');
        if (!file_exists($base_path_slider_mobile . $store_path)) {
            \File::makeDirectory($base_path_slider_mobile . $store_path, 0777, true);
        }
        foreach ($sizes as $size) {
            $this->generateSize($image_name, $size);
        }
    }

    /**
     * generate default sizes
     *
     * @param string $image_name
     * @return void
     */
    protected function generateDefaultSizes(string $image_name)
    {
        $sizes = config('media.sizes.default');
        foreach ($sizes as $size) {
            $this->generateSize($image_name, $size);
        }
    }

    /**
     * generate size
     *
     * @param string $image_name
     * @param array $data
     * @return void
     */
    protected function generateSize(string $image_name, array $data)
    {
        $this->createDirectory($data['slug']);
        $width = $data['width'];
        $height = $data['height'];
        $path = storage_path() . config('media.path.upload') . $data['slug'] . DIRECTORY_SEPARATOR;
        $originalImage = storage_path() . config('media.path.original') . $image_name;

        $logger = getLogger('upload-image');
//        $logger->debug('Original Image path  : ' .$originalImage);

        $img = Image::make($originalImage);
        $img_width = $img->width();
        $img_height = $img->height();

        // check new image size
        $needToResize = $img_width > $width || $img_height > $height;
        if (!$needToResize) {
//            $logger->debug('Not saving as no resize needed: ' .$image_name);
            return;
        }

        // resize the image to a width of size and constrain aspect ratio (auto height)
        if ($img_width > $img_height) {
            $img->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . $image_name, 100);
//            $logger->debug("Image Resizes With 100% height, width constrained to : {$width}" .$image_name);
            return;
        }

        // resize the image to a height of size and constrain aspect ratio (auto width)
        $img->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path . $image_name, 100);
//        $logger->debug("Image Resizes With 100% width , height constrained to : {$height}" .$image_name);
    }

    /**
     * create size directory
     *
     * @param string $directory
     * @return void
     */
    protected function createDirectory(string $directory)
    {
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
    }
}
