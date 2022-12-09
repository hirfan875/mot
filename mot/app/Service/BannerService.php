<?php

namespace App\Service;

use App\Models\Banner;
use App\Models\BannerTranslate;

class BannerService
{
    /**
     * create new banner
     *
     * @param array $request
     * @return Banner
     */
    public function create(array $request): Banner
    {
        $banner = new Banner();
        $banner->title = $request['title'][getDefaultLocaleId()];
        // upload new file
        if (isset($request['image'][getDefaultLocaleId()])) {
            $banner->image = Media::upload($request['image'][getDefaultLocaleId()], true, true, 'image');
        }
        // upload new file
        if (isset($request['image_mobile'][getDefaultLocaleId()])) {
            $banner->image_mobile = Media::upload($request['image_mobile'][getDefaultLocaleId()], true, true, 'image');
        }
//        $banner->image = Media::handle($request, 'image');
//        $banner->image_mobile = Media::handle($request, 'image_mobile');
        $banner->button_text = $request['button_text'][getDefaultLocaleId()];
        $banner->button_url = $request['button_url'];
        $banner->data = $request['data'][getDefaultLocaleId()];
        $banner->save();
        
        $results = $this->saveBannerTranslateFromRequest($request, $banner);
        $homepageSectionsService = new HomepageSectionsService();
        $homepageSectionsService->set_sort_order($banner);

        return $banner;
    }

    /**
     * update banner
     *
     * @param Banner $banner
     * @param array $request
     * @return Banner
     */
    public function update(Banner $banner, array $request): Banner
    {
        $banner->title = $request['title'][getDefaultLocaleId()];
        // upload new file
        if (isset($request['image'][getDefaultLocaleId()])) {
            $banner->image = Media::upload($request['image'][getDefaultLocaleId()], true, true, 'image');
        }
        // upload new file
        if (isset($request['image_mobile'][getDefaultLocaleId()])) {
            $banner->image_mobile = Media::upload($request['image_mobile'][getDefaultLocaleId()], true, true, 'image');
        }
//        $banner->image = Media::handle($request, 'image', $banner);
//        $banner->image_mobile = Media::handle($request, 'image_mobile', $banner);
        $banner->button_text = $request['button_text'][getDefaultLocaleId()];
        $banner->button_url = $request['button_url'];
        $banner->data = $request['data'][getDefaultLocaleId()];
        $banner->save();
        $results = $this->updateBannerTranslateFromRequest($request, $banner);
        
        return $banner;
    }

    /**
     * delete banner
     *
     * @param Banner $banner
     * @return void
     */
    public function delete(Banner $banner)
    {
        Media::delete($banner->image);
        Media::delete($banner->image_mobile);
        $sort_order = $banner->sort->sort_order;
        $banner->sort->delete();
        $banner->delete();

        $homepageSectionsService = new HomepageSectionsService();
        $homepageSectionsService->decrement_sort_order($sort_order);
    }
    
    /**
     * set banner translate data from request
     *
     * @param array $request
     * @param BannerTranslate $bannerTranslate
     */
    private function saveBannerTranslateFromRequest(array $request, Banner $banner) {
        foreach (getLocaleList() as $row) {
            $this->includeBannerTranslateArr($request, $banner, $row);
        }
    }

    private function includeBannerTranslateArr(array $request, Banner $banner, $row) {
        $brandTranslate = BannerTranslate::firstOrNew(['banner_id' => $banner->id, 'language_id' => $row->id ]);
        $brandTranslate->banner_id = $banner->id;
        $brandTranslate->language_id = $row->id;
        $brandTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        if (isset($request['image'][$row->id])) {
            $brandTranslate->image = Media::upload($request['image'][$row->id], true, true, 'image');
        } else {
            $brandTranslate->image = Media::upload($request['image'][getDefaultLocaleId()], true, true, 'image');
        }
        
        if (isset($request['image_mobile'][$row->id])) {
            $brandTranslate->image_mobile = Media::upload($request['image_mobile'][$row->id], true, true, 'image');
        } else {
            $brandTranslate->image_mobile = Media::upload($request['image_mobile'][getDefaultLocaleId()], true, true, 'image');
        }
        $brandTranslate->button_text = $request['button_text'][$row->id] ? $request['button_text'][$row->id] : $request['button_text'][getDefaultLocaleId()];
        $brandTranslate->data = $request['data'][$row->id] ? $request['data'][$row->id] : $request['data'][getDefaultLocaleId()];
        $brandTranslate->status = true;
        $brandTranslate->save();
    }
    
    /**
     * update banner translate data from request
     *
     * @param array $request
     * @param BannerTranslate $bannerTranslate
     */
    private function updateBannerTranslateFromRequest(array $request, Banner $banner) {
        foreach (getLocaleList() as $row) {
            $this->updateBannerTranslateArr($request, $banner, $row);
        }
    }
    
    private function updateBannerTranslateArr(array $request, Banner $banner, $row) {
        $brandTranslate = BannerTranslate::firstOrNew(['banner_id' => $banner->id, 'language_id' => $row->id ]);
        $brandTranslate->banner_id = $banner->id;
        $brandTranslate->language_id = $row->id;
        $brandTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        if (isset($request['image'][$row->id])) {
            $brandTranslate->image = Media::upload($request['image'][$row->id], true, true, 'image');
        }
        if (isset($request['image_mobile'][$row->id])) {
            $brandTranslate->image_mobile = Media::upload($request['image_mobile'][$row->id], true, true, 'image');
        } 
        $brandTranslate->button_text = $request['button_text'][$row->id] ? $request['button_text'][$row->id] : $request['button_text'][getDefaultLocaleId()];
        $brandTranslate->data = $request['data'][$row->id] ? $request['data'][$row->id] : $request['data'][getDefaultLocaleId()];
        $brandTranslate->status = true;
        $brandTranslate->save();
    }
}
