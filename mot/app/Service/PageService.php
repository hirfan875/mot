<?php

namespace App\Service;

use App\Models\Page;
use App\Models\PageTranslate;

class PageService
{
    /**
     * create new page
     *
     * @param array $request
     * @return Page
     */
    public function create(array $request): Page
    {
        $page = new Page();
        $page->title = $request['title'][getDefaultLocaleId()];
        $page->data = $request['data'][getDefaultLocaleId()];
        $page->meta_title = $request['meta_title'][getDefaultLocaleId()];
        $page->meta_desc = $request['meta_desc'][getDefaultLocaleId()];
        $page->meta_keyword = $request['meta_keyword'][getDefaultLocaleId()];
        $page->save();
        $results = $this->savePageTranslateFromRequest($request, $page);

        return $page;
    }

    /**
     * update page
     *
     * @param Page $page
     * @param array $request
     * @return Page
     */
    public function update(Page $page, array $request): Page
    {
        $page->title = $request['title'][getDefaultLocaleId()];
        $page->data = $request['data'][getDefaultLocaleId()];
        $page->meta_title = $request['meta_title'][getDefaultLocaleId()];
        $page->meta_desc = $request['meta_desc'][getDefaultLocaleId()];
        $page->meta_keyword = $request['meta_keyword'][getDefaultLocaleId()];
        $page->save();
        $results = $this->updatePageTranslateFromRequest($request, $page);

        return $page;
    }
    
    
    /**
     * set Page translate data from request
     *
     * @param array $request
     * @param PageTranslate $pageTranslate
     */
    private function savePageTranslateFromRequest(array $request, Page $page) {
        foreach (getLocaleList() as $row) {
            $this->includePageTranslateArr($request, $page, $row);
        }
    }

    private function includePageTranslateArr(array $request, Page $page, $row) {
        $brandTranslate = PageTranslate::firstOrNew(['page_id' => $page->id, 'language_id' => $row->id ]);
        $brandTranslate->page_id = $page->id;
        $brandTranslate->language_id = $row->id;
        $brandTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        $brandTranslate->data = $request['data'][$row->id] ? $request['data'][$row->id] : $request['data'][getDefaultLocaleId()];
        $brandTranslate->meta_title = $request['meta_title'][$row->id] ? $request['meta_title'][$row->id] : $request['meta_title'][getDefaultLocaleId()];
        $brandTranslate->meta_desc = $request['meta_desc'][$row->id] ? $request['meta_desc'][$row->id] : $request['meta_desc'][getDefaultLocaleId()];
        $brandTranslate->meta_keyword = $request['meta_keyword'][$row->id] ? $request['meta_keyword'][$row->id] : $request['meta_keyword'][getDefaultLocaleId()];
        $brandTranslate->status = true;
        $brandTranslate->save();
    }
    
    /**
     * update page translate data from request
     *
     * @param array $request
     * @param PageTranslate $pageTranslate
     */
    private function updatePageTranslateFromRequest(array $request, Page $page) {
        foreach (getLocaleList() as $row) {
            $this->includePageTranslateArr($request, $page, $row);
        }
    }
}
