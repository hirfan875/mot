<?php

namespace App\Service;

use App\Models\TrendingProduct;

class TrendingProductService
{
    /**
     * create new section
     *
     * @param array $request
     * @return TrendingProduct
     */
    public function create(array $request): TrendingProduct
    {
        $section = new TrendingProduct();

        $section->title = $request['title'];
        $section->type = $request['type'];
        $section->products_type = $request['products_type'];
        $section->view_all_url = $request['view_all_url'];

        if ($request['type'] === 'category') {
            $section->category_id = $request['category_id'];
        }

        if ($request['products_type'] === 'tag') {
            $section->tag_id = $request['tag_id'];
        }

        $section->save();

        $homepageSectionsService = new HomepageSectionsService();
        $homepageSectionsService->set_sort_order($section);

        return $section;
    }

    /**
     * update section
     *
     * @param TrendingProduct $section
     * @param array $request
     * @return TrendingProduct
     */
    public function update(TrendingProduct $section, array $request): TrendingProduct
    {
        $section->title = $request['title'];
        $section->type = $request['type'];
        $section->products_type = $request['products_type'];
        $section->view_all_url = $request['view_all_url'];

        if ($request['type'] === 'category') {
            $section->category_id = $request['category_id'];
        }

        if ($request['products_type'] === 'tag') {
            $section->tag_id = $request['tag_id'];
            $section->category_id = null;
        }

        $section->save();

        return $section;
    }
}
