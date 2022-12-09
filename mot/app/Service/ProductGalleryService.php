<?php

namespace App\Service;

use App\Models\ProductGallery;
use DB;
use \App\Models\Product;
class ProductGalleryService
{
    /**
     * upload gallery
     *
     * @param array $request
     * @return string|array
     */
    public function upload(array $request)
    {
        if (isset($request['file']) && !empty($request['file'])) {

            $gallery_response = [];
            foreach ($request['file'] as $file) {
                $slug = '';
                $row = '';
                if(isset($request['product_id'])) {
                    $row =  Product::findOrFail($request['product_id']);
                }
                $imageName = Media::upload($file, true, false, 'product',$row);
                $response['name'] = $imageName;

                // save in DB
                if (isset($request['product_id']) && !empty($request['product_id'])) {
                    $productGallery = $this->insertProductGallery($request['product_id'], $imageName);
                    $gallery_response[] = ['name' => $imageName, 'id' => $productGallery->id];
                    $response = ['gallery' => $gallery_response];
                }
            }

            return $response;
        } else {
            return "error";
        }
    }

    /**
     * delete gallery image
     *
     * @param array $request
     * @return string
     */
    public function delete(array $request): string
    {
        if (isset($request['filename']) && !empty($request['filename'])) {

            $filename = $request['filename'];

            // if product id exist remove file from DB
            if (isset($request['product_id']) && !empty($request['product_id'])) {

                $productImage = ProductGallery::whereProductId($request['product_id'])->whereImage($filename)->first();
                if ($productImage) {
                    $productImage->deleteImage($request['product_id']);
                }
            }

            Media::delete($filename);

            return "success";
        } else {
            return "error";
        }
    }

    /**
     * update gallery sorting order
     *
     * @param array $request
     * @return void
     */
    public function updateSortingOrder(array $request)
    {
        if (isset($request['items']) && !empty($request['items'])) {
            foreach ($request['items'] as $k => $r) {
                ProductGallery::whereId($r)->update(['sort_order' => $k]);
            }
        }
    }

    /**
     * insert product gallery
     *
     * @param int $product_id
     * @param string $imageName
     * @return ProductGallery
     */
    private function insertProductGallery(int $product_id, string $imageName): ProductGallery
    {
        $sort_order = ProductGallery::whereProductId($product_id)->count();

        $productGallery = new ProductGallery();

        $productGallery->product_id = $product_id;
        $productGallery->image = $imageName;
        $productGallery->sort_order = $sort_order;

        $productGallery->save();

        return $productGallery;
    }
}
