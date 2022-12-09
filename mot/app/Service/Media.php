<?php

namespace App\Service;

use App\Jobs\ResizeImageProcess;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Media
{
    /**
     * upload file
     *
     * @param object $file
     * @param bool $resize
     * @param bool $unique
     * @param string $media_type
     * @return string
     */
    public static function upload(object $file, bool $resize = true, bool $unique = true, string $media_type = '', $row = null ): string
    {
        $file_name = self::getFileName($file, $unique);
        $store_path ='';
        if ($row != null) {

            $table = $row->getTable();
            $slug = isset($row->store->slug) ? $row->store->slug : $row->slug;
            $store_path = $slug . '/' . $table . '/' . date('Y') . '/' . date('m') . '/';

            $base_path_original = public_path('storage/original/');
            if (!file_exists($base_path_original . $store_path)) {
                \File::makeDirectory($base_path_original . $store_path, 0777, true);
            }
            $base_path_product_listing = public_path('storage/product_listing/');
            if (!file_exists($base_path_product_listing . $store_path)) {
                \File::makeDirectory($base_path_product_listing . $store_path, 0777, true);
            }
            $base_path_product_detail = public_path('storage/product_detail/');
            if (!file_exists($base_path_product_detail . $store_path)) {
                \File::makeDirectory($base_path_product_detail . $store_path, 0777, true);
            }
            $base_path_product_thumbnail = public_path('storage/product_thumbnail/');
            if (!file_exists($base_path_product_thumbnail . $store_path)) {
                \File::makeDirectory($base_path_product_thumbnail . $store_path, 0777, true);
            }
            $base_path_thumbnail = public_path('storage/thumbnail/');
            if (!file_exists($base_path_thumbnail . $store_path)) {
                \File::makeDirectory($base_path_thumbnail . $store_path, 0777, true);
            }
            $base_path_medium = public_path('storage/medium/');
            if (!file_exists($base_path_medium . $store_path)) {
                \File::makeDirectory($base_path_medium . $store_path, 0777, true);
            }
            $base_path_large = public_path('storage/large/');
            if (!file_exists($base_path_large . $store_path)) {
                \File::makeDirectory($base_path_large . $store_path, 0777, true);
            }
            
        }

        $logger = getLogger('upload-image');
//        $logger->debug('Uploaded Image : ' .$file_name , [$media_type]);
       
        $file->storeAs('original', $file_name);
//        self::compress($file_name,$file);
         
        // dispatch resize image process
        if ($resize == true) {
//            $logger->debug('Resizing Image : ' .$file_name , [$media_type]);
            ResizeImageProcess::dispatch($file_name, $media_type);
        }

        return $file_name;
    }

    public static function compress($file_name,$file)
    {
        try {
            $originalImage = storage_path() . config('media.path.original') . $file_name;
            $path = storage_path() . config('media.path.thumbnail')  . $file_name; // $originalImage;

            $img = \Image::make($originalImage);
            $imgInfo = getimagesize($file); 
            $mime = $imgInfo['mime']; 
             
            // Create a new image from file 
            switch($mime){ 
                case 'image/jpeg': 
                    $image = imagecreatefromjpeg($originalImage); 
                    break; 
                case 'image/png': 
                    $image = imagecreatefrompng($originalImage); 
                    break; 
                case 'image/gif': 
                    $image = imagecreatefromgif($originalImage); 
                    break; 
                default: 
                    $image = imagecreatefromjpeg($originalImage); 
            } 
             
            imagejpeg($image, $path, 90); 
        } catch (\Exception $e) {
            return __('Invalid image format.');
        }
    }

    /**
     * delete file
     *
     * @param string/null $file
     * @return void
     */
    public static function delete(?string $file): void
    {
        if (!empty($file)) {
            Storage::delete("original/{$file}");
            Storage::delete("thumbnail/{$file}");
            Storage::delete("medium/{$file}");
            Storage::delete("large/{$file}");
            Storage::delete("product_listing/{$file}");
            Storage::delete("product_detail/{$file}");
            Storage::delete("product_thumbnail/{$file}");
            Storage::delete("deal_home/{$file}");
            Storage::delete("sponsor_category/{$file}");
        }

        return;
    }

    /**
     * handle form file upload
     *
     * @param array $request
     * @param string $file_name
     * @param Model/null $row
     * @param string $media_type
     * @return string/null
     */
    public static function handle(array $request, string $file_name, ?Model $row = null, string $media_type = ''): ?string
    {
        $row_imaes='';
        if(isset($row->image)){
            $row_imaes = $row->image;
        }
        
        $old_file = ( $row_imaes != null ? $row->getOriginalMedia($file_name) : null);
        $value = self::deleteOldFileIfRequested($request, $file_name, $old_file);

        // upload new file
        if (isset($request[$file_name])) {
            $value = self::upload($request[$file_name], true, true, $media_type,$row);
        }

        return $value;
    }

    /**
     * delete old file if requested
     *
     * @param array $request
     * @param string $file_name
     * @param string|null $old_file
     * @return string|null
     */
    protected static function deleteOldFileIfRequested(array $request, string $file_name, ?string $old_file): ?string
    {
        if (isset($request['remove_' . $file_name]) && $request['remove_' . $file_name] === 'Yes') {
            return self::delete($old_file);
        }

        if ($old_file != null && isset($request[$file_name])) {
            return self::delete($old_file);
        }

        return $old_file;
    }

    /**
     * get unique file name
     *
     * @param object $file
     * @param bool $unique
     * @return string
     */
    protected static function getFileName(object $file, bool $unique = true): string
    {
        $file_name = $file->getClientOriginalName();
        if ($unique) {
            $file_name = uniqid() . '_' . $file_name;
        }

        $file_name = str_replace(' ', '_', $file_name);
        $file_name = preg_replace("/[^a-z0-9\_\-\.]/i", "", $file_name);

        return $file_name;
    }

    /**
     * save crop image
     *
     * @param array $request
     * @param string $size
     * @param string|null $file_name
     * @param string $field_name
     * @return void
     */
    public static function saveCropImage(array $request, string $size, ?string $file_name, string $field_name = 'image')
    {
        if (isset($request['new_crop_' . $field_name]) && !empty($request['new_crop_' . $field_name])) {

            self::createDirectory($size);
            $explodeImage = explode(";base64,", $request['new_crop_' . $field_name]);
            $new_image = base64_decode($explodeImage[1]);
            $imagePath = $size . DIRECTORY_SEPARATOR . $file_name;
            $logger = getLogger('saveCropImage');
            $logger->info('Saving ' . $imagePath, ['size' => $size, 'file_name' => $file_name, 'field_name' => $field_name]);
            Storage::put($imagePath, $new_image);
        }
    }

    /**
     * create size directory
     *
     * @param string $directory
     * @return void
     */
    public static function createDirectory(string $directory)
    {
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
    }
}
