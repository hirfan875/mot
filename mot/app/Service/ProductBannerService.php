<?php

namespace App\Service;

use App\Models\ProductBanner;

class ProductBannerService
{
    /**
     * create new product banner
     *
     * @param array $request
     * @return ProductBanner
     */
    public function create(array $request): ProductBanner
    {
        $productBanner = new ProductBanner();

        $productBanner->banner_1 = Media::handle($request, 'banner_1');
        $productBanner->banner_1_url = $request['banner_1_url'];
        $productBanner->banner_2 = Media::handle($request, 'banner_2');
        $productBanner->banner_2_url = $request['banner_2_url'];
        $productBanner->save();

        $productBanner->categories()->sync($request['categories']);

        return $productBanner;
    }

    /**
     * update product banner
     *
     * @param ProductBanner $productBanner
     * @param array $request
     * @return ProductBanner
     */
    public function update(ProductBanner $productBanner, array $request): ProductBanner
    {
        $productBanner->banner_1 = Media::handle($request, 'banner_1', $productBanner);
        $productBanner->banner_1_url = $request['banner_1_url'];
        $productBanner->banner_2 = Media::handle($request, 'banner_2', $productBanner);
        $productBanner->banner_2_url = $request['banner_2_url'];
        $productBanner->save();

        if (isset($request['categories'])) {
            $productBanner->categories()->sync($request['categories']);
        }

        return $productBanner;
    }

    /**
     * get page banners by category id
     *
     * @param int $category_id
     * @return ProductBanner
     */
    public function getPageBanners(int $category_id = null): ProductBanner
    {
        if ($category_id === null) {
            return ProductBanner::whereIsDefault(true)->first();
        }

        $checkCategoryBanners = ProductBanner::whereHas('categories', function ($query) use ($category_id) {
            return $query->where('category_id', $category_id);
        })->first();
        if ($checkCategoryBanners) {
            return $checkCategoryBanners;
        }

        return ProductBanner::whereIsDefault(true)->first();
    }
}
