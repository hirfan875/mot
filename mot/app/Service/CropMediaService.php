<?php

namespace App\Service;

use App\Models\DailyDeal;
use App\Models\FlashDeal;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\SponsorCategory;
use Illuminate\Database\Eloquent\Model;

class CropMediaService
{
    /**
     * check media type is valid or not
     *
     * @param array $request
     * @return bool
     */
    public function checkMediaType(array $request): bool
    {
        if (!isset($request['type']) || empty($request['type'])) {
            return false;
        }

        if (!in_array($request['type'], ['product', 'product_main', 'daily_deal', 'flash_deal', 'sponsor_category'])) {
            return false;
        }

        return true;
    }

    /**
     * get image data
     *
     * @param array $request
     * @return Model
     */
    public function getImageData(array $request): Model
    {
        if ($request['type'] === 'product') {
            return $this->getProductImage($request);
        }

        if ($request['type'] === 'product_main') {
            return $this->getProductMainImage($request);
        }

        if ($request['type'] === 'daily_deal') {
            return $this->getDailyDealImage($request);
        }

        if ($request['type'] === 'flash_deal') {
            return $this->getFlashDealImage($request);
        }

        if ($request['type'] === 'sponsor_category') {
            return $this->getSponsorCategoryImage($request);
        }
    }

    /**
     * get product image
     *
     * @param array $request
     * @return ProductGallery
     */
    protected function getProductImage(array $request): ProductGallery
    {
        return ProductGallery::whereProductId($request['foreign_id'])->whereId($request['image_id'])->firstOrFail();
    }

    /**
     * get product main image
     *
     * @param array $request
     * @return Product
     */
    protected function getProductMainImage(array $request): Product
    {
        return Product::whereId($request['image_id'])->firstOrFail();
    }

    /**
     * get daily deal image
     *
     * @param array $request
     * @return DailyDeal
     */
    protected function getDailyDealImage(array $request): DailyDeal
    {
        return DailyDeal::whereId($request['image_id'])->firstOrFail();
    }

    /**
     * get flash deal image
     *
     * @param array $request
     * @return FlashDeal
     */
    protected function getFlashDealImage(array $request): FlashDeal
    {
        return FlashDeal::whereId($request['image_id'])->firstOrFail();
    }

    /**
     * get sponsor category image
     *
     * @param array $request
     * @return SponsorCategory
     */
    protected function getSponsorCategoryImage(array $request): SponsorCategory
    {
        return SponsorCategory::whereId($request['image_id'])->firstOrFail();
    }

    /**
     * get type available sizes
     *
     * @param string $type
     * @return array
     */
    public function getTypeAvailableSizes(string $type): array
    {
        if ($type === 'product' || $type === 'product_main') {
            return config('media.sizes.product');
        }

        if ($type === 'daily_deal' || $type === 'flash_deal') {
            return config('media.sizes.deal');
        }

        if ($type === 'sponsor_category') {
            return config('media.sizes.sponsor_category');
        }
    }
}
