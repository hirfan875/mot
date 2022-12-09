<?php

namespace App\Service;

use App\Models\Brand;
use App\Models\BrandTranslate;

class BrandService
{
    /**
     * create new brand
     *
     * @param array $request
     * @return Brand
     */
    public function create(array $request): Brand
    {
        $brand = new Brand();
        $brand->title = $request['title'][getDefaultLocaleId()];
        $brand->store_id = $request['store'];
        $brand->image = Media::handle($request, 'image');
        $brand->data = $request['data'][getDefaultLocaleId()];
        $brand->meta_title = $request['meta_title'][getDefaultLocaleId()];
        $brand->meta_desc = $request['meta_desc'][getDefaultLocaleId()];
        $brand->meta_keyword = $request['meta_keyword'][getDefaultLocaleId()];
        $brand->sort_order = $this->getSortOrderNumber();
        $brand->is_approved = true;
        $brand->save();

        $results = $this->saveBrandTranslateFromRequest($request, $brand);

        return $brand;
    }

    /**
     * update brand
     *
     * @param Brand $brand
     * @param array $request
     * @return Brand
     */
    public function update(Brand $brand, array $request): Brand
    {
        $brand->title = $request['title'][getDefaultLocaleId()];
        $brand->store_id = $request['store'];
        $brand->image = Media::handle($request, 'image', $brand);
        $brand->data = $request['data'][getDefaultLocaleId()];
        $brand->meta_title = $request['meta_title'][getDefaultLocaleId()];
        $brand->meta_desc = $request['meta_desc'][getDefaultLocaleId()];
        $brand->meta_keyword = $request['meta_keyword'][getDefaultLocaleId()];
        $brand->is_approved = true;
        if(isset($request['slug']) ){
            $brand->slug = str_replace(" ","-",$request['slug']);
        }
        $brand->save();
        $results = $this->updateBrandTranslateFromRequest($request, $brand);

        return $brand;
    }

    /**
     * get sort order number
     *
     * @return int
     */
    private function getSortOrderNumber(): int
    {
        return Brand::count();
    }

    /**
     * get brand row
     * @param string $slug
     * @return row
     */
    public function getBySlug(string $slug): ?Brand
    {
        $brand = Brand::whereSlug($slug)->first();
        return $brand;
    }

    /**
     * get brand row
     * @param string $title
     * @return row
     */
    public function getByTitle(string $title): ?Brand
    {
        $brand = Brand::whereTitle($title)->first();
        return $brand;
    }

    /**
     * set brand translate data from request
     *
     * @param array $request
     * @param BrandTranslate $brandTranslate
     */
    private function saveBrandTranslateFromRequest(array $request, Brand $brand) {
        foreach (getLocaleList() as $row) {
            $this->includeBrandTranslateArr($request, $brand, $row);
        }
    }

    private function includeBrandTranslateArr(array $request, Brand $brand, $row) {
        $brandTranslate = BrandTranslate::firstOrNew(['brand_id' => $brand->id, 'language_id' => $row->id ]);
        $brandTranslate->brand_id = $brand->id;
        $brandTranslate->language_id = $row->id;
        $brandTranslate->language_code = $row->code;
        $brandTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        $brandTranslate->data = $request['data'][$row->id] ? $request['data'][$row->id] : $request['data'][getDefaultLocaleId()];
        $brandTranslate->meta_title = $request['meta_title'][$row->id] ? $request['meta_title'][$row->id] : $request['meta_title'][getDefaultLocaleId()];
        $brandTranslate->meta_desc = $request['meta_desc'][$row->id] ? $request['meta_desc'][$row->id] : $request['meta_desc'][getDefaultLocaleId()];
        $brandTranslate->meta_keyword = $request['meta_keyword'][$row->id] ? $request['meta_keyword'][$row->id] : $request['meta_keyword'][getDefaultLocaleId()];
        $brandTranslate->status = true;
        $brandTranslate->save();
    }

    /**
     * update brand translate data from request
     *
     * @param array $request
     * @param BrandTranslate $brandTranslate
     */
    private function updateBrandTranslateFromRequest(array $request, Brand $brand) {
        foreach (getLocaleList() as $row) {
            $this->includeBrandTranslateArr($request, $brand, $row);
        }
    }

    /**
     * @return mixed
     */
    public function getAll($perPage = null)
    {
        return Brand::where('is_approved' , 1)->whereStatus(true)->paginate($perPage);
    }

    /**
     * create seller brand
     *
     * @param array $request
     * @return Brand
     */
    public function createSellerBrand(array $request): Brand
    {
        $user = auth()->user();
        $title = $request['additional-brand'];
        $brand = Brand::firstOrCreate(['title' => $title]);
        $brand->title = $title;
        $brand->store_id = $user->store_id;
        $brand->is_approved = false;
        $brand->save();

        return $brand;
    }
    
    /**
     * create seller brand
     *
     * @param array $request
     * @return Brand
     */
    public function createSellerBrandTrendyol(array $request): Brand
    {
        $user = auth()->user();
        $id = $request['brandId'];
        $brand = Brand::firstOrCreate(['id' => $id]);
        $brand->id = $request['brandId'];
        $brand->title = $request['additional-brand'];
        $brand->store_id = $request['store'];
        $brand->is_approved = true;
        $brand->save();

        return $brand;
    }
    
    public function createTrendyol(array $request): Brand
    {
        $brand = new Brand();
        $brand->id = $request['id'];
        $brand->title = $request['title'];
        $brand->status = $request['status'];
        $brand->sort_order = $this->getSortOrderNumber();
        $brand->is_approved = true;
        $brand->save();

        $results = $this->saveBrandTranslateFromRequestTrendyol($request, $brand);

        return $brand;
    }
    
    private function saveBrandTranslateFromRequestTrendyol(array $request, Brand $brand) {
        foreach (getLocaleList() as $row) {
            $this->includeBrandTranslateArrTrendyol($request, $brand, $row);
        }
    }

    private function includeBrandTranslateArrTrendyol(array $request, Brand $brand, $row) {
        $brandTranslate = BrandTranslate::firstOrNew(['brand_id' => $brand->id, 'language_id' => $row->id ]);
        $brandTranslate->brand_id = $brand->id;
        $brandTranslate->language_id = $row->id;
        $brandTranslate->language_code = $row->code;
        $brandTranslate->title = $request['title'];
        $brandTranslate->status = true;
        $brandTranslate->save();
    }
}
