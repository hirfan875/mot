<?php

namespace App\Service;

use App\Models\TrendyolCategories;

class TrendyolCategoryService 
{

    /**
     * create new trendyolCategories
     *
     * @param array $request
     * @return TrendyolCategories
     */
    public function create(array $request): TrendyolCategories 
    {
        $trendyolCategories = new TrendyolCategories();

        $this->saveCategoryFromRequest($trendyolCategories, $request);
        $trendyolCategories->save();

        return $trendyolCategories;
    }

    /**
     * save trendyolCategories from request
     *
     * @param TrendyolCategories $trendyolCategories
     * @param array $request
     * @param int|null $parent_id
     * @return void
     */
    private function saveCategoryFromRequest(TrendyolCategories $trendyolCategories, array $request) 
    {
        $trendyolCategories->id = $request['id'];
        $trendyolCategories->parent_id = $request['parent_id'];
        $trendyolCategories->title = $request['title'];
        $trendyolCategories->status = $request['status'];
    }

}
