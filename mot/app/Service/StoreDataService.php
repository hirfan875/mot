<?php

namespace App\Service;

use App\Models\StoreData;
use App\Models\StoreProfileTranslate;
use App\Models\Store;

class StoreDataService
{
    /**
     * create new store data
     *
     * @param array $request
     * @param int $store_id
     * @return StoreData
     */
    public function create(array $request, int $store_id): StoreData
    {
        $storeData = new StoreData();
        $storeData->store_id = $store_id;
        $storeData->status = StoreData::STATUS_APPROVED;
        $storeData->banner = Media::handle($request, 'banner');
        $storeData->logo = Media::handle($request, 'logo');
        $this->setDataFields($storeData, $request);
        $storeData->save();

        $results = $this->saveStoreProfileTranslateFromRequest($request, $store_id, $storeData);

        return $storeData;
    }
    
    
    public function createData(array $request, Store $store): StoreData
    {
        $storeData = new StoreData();
        $storeData->store_id = $store->id;
        $storeData->status = StoreData::STATUS_APPROVED;
        $storeData->save();
        return $storeData;
    }

    /**
     * update store data
     *
     * @param StoreData $storeData
     * @param array $request
     * @return StoreData
     */
    public function update(StoreData $storeData, array $request): StoreData
    {

        if (isset($request['banner'])) {
            $storeData->banner = Media::handle($request, 'banner', $storeData);
        }
        if (isset($request['logo'])) {
            $storeData->logo = Media::handle($request, 'logo', $storeData);
        }
        if (isset($request['remove_banner']) && $request['remove_banner'] == 'Yes') {
            $storeData->banner = Media::handle($request, 'banner', $storeData);
        }
        if (isset($request['remove_logo']) && $request['remove_logo'] == 'Yes') {
            $storeData->logo = Media::handle($request, 'logo', $storeData);
        }
        $this->setDataFields($storeData, $request);
        $storeData->save();

        $results = $this->updateStoreProfileTranslateFromRequest($request, $storeData->store_id, $storeData);

        return $storeData;
    }

    /**
     * update or create storeData
     *
     * @param int $store_id
     * @param array $request
     * @return StoreData
     */
    public function updateOrCreate(int $store_id, array $request): StoreData
    {
        $storeData = StoreData::whereStoreId($store_id)->whereStatus(StoreData::STATUS_PENDING)->first();

        if (!$storeData) {
            $storeData = new StoreData();
            $storeData->store_id = $store_id;
            $storeData->banner = null;
            $storeData->logo = null;
        }
        
        $storeData->banner = Media::handle($request, 'banner', $storeData);
        $storeData->logo = Media::handle($request, 'logo', $storeData);
        
        $this->setDataFields($storeData, $request);
        $storeData->save();
        $results = $this->updateStoreProfileTranslateFromRequest($request, $store_id, $storeData);

        return $storeData;
    }

    /**
     * set data fields
     *
     * @param StoreData $storeData
     * @param array $request
     * @return void
     */
    protected function setDataFields(StoreData $storeData, array $request): void
    {
        $storeData->description = $request['description'][getDefaultLocaleId()];
        $storeData->policies = $request['policies'][getDefaultLocaleId()];
        $storeData->meta_title = $request['meta_title'][getDefaultLocaleId()];
        $storeData->meta_desc = $request['meta_desc'][getDefaultLocaleId()];
        $storeData->meta_keyword = $request['meta_keyword'][getDefaultLocaleId()];
    }

    /**
     * set brand translate data from request
     *
     * @param array $request
     * @param StoreProfileTranslate $storeProfileTranslate
     */
    private function saveStoreProfileTranslateFromRequest(array $request, $store_id, StoreData $storeData) 
    {
        foreach (getLocaleList() as $row) {
            $this->includeStoreProfileTranslateArr($request, $store_id, $storeData, $row);
        }
    }

    private function includeStoreProfileTranslateArr(array $request,int $store_id, StoreData $storeData, $row) 
    {
        $storeProfileTranslate = new StoreProfileTranslate();
        $storeProfileTranslate->store_id = $store_id;
        $storeProfileTranslate->language_id = $row->id;
        $storeProfileTranslate->language_code = $row->code;
        $storeProfileTranslate->description = $request['description'][$row->id] ? $request['description'][$row->id] : $request['description'][getDefaultLocaleId()];
        $storeProfileTranslate->policies = $request['policies'][$row->id] ? $request['policies'][$row->id] : $request['policies'][getDefaultLocaleId()];
        $storeProfileTranslate->meta_title = $request['meta_title'][$row->id] ? $request['meta_title'][$row->id] : $request['meta_title'][getDefaultLocaleId()];
        $storeProfileTranslate->meta_desc = $request['meta_desc'][$row->id] ? $request['meta_desc'][$row->id] : $request['meta_desc'][getDefaultLocaleId()];
        $storeProfileTranslate->meta_keyword = $request['meta_keyword'][$row->id] ? $request['meta_keyword'][$row->id] : $request['meta_keyword'][getDefaultLocaleId()];
        $storeProfileTranslate->status = true;
        $storeProfileTranslate->save();
    }

    /**
     * update brand translate data from request
     *
     * @param array $request
     * @param StoreProfileTranslate $storeProfileTranslate
     */
    private function updateStoreProfileTranslateFromRequest(array $request, $store_id, StoreData $storeData) 
    {
        foreach (getLocaleList() as $row) {
            $storeProfileTranslate = StoreProfileTranslate::firstOrNew(['store_id' => $store_id, 'language_id' => $row->id ]);
            $storeProfileTranslate->store_id = $store_id;
            $storeProfileTranslate->language_id = $row->id;
            $storeProfileTranslate->language_code = $row->code;
            if(isset($request['name'])){
            $storeProfileTranslate->name = $request['name'][$row->id] ? $request['name'][$row->id] : $request['name'][getDefaultLocaleId()];
            }
            $storeProfileTranslate->description = $request['description'][$row->id] ? $request['description'][$row->id] : $request['description'][getDefaultLocaleId()];
            $storeProfileTranslate->policies = $request['policies'][$row->id] ? $request['policies'][$row->id] : $request['policies'][getDefaultLocaleId()];
            $storeProfileTranslate->meta_title = $request['meta_title'][$row->id] ? $request['meta_title'][$row->id] : $request['meta_title'][getDefaultLocaleId()];
            $storeProfileTranslate->meta_desc = $request['meta_desc'][$row->id] ? $request['meta_desc'][$row->id] : $request['meta_desc'][getDefaultLocaleId()];
            $storeProfileTranslate->meta_keyword = $request['meta_keyword'][$row->id] ? $request['meta_keyword'][$row->id] : $request['meta_keyword'][getDefaultLocaleId()];
            $storeProfileTranslate->status = true;
            $storeProfileTranslate->save();
        }
    }
}
