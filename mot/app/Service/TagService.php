<?php

namespace App\Service;

use App\Models\Tag;
use App\Models\TagTranslate;

class TagService
{
    /**
     * create new tag
     *
     * @param array $request
     * @return Tag
     */
    public function create(array $request): Tag
    {
        $tag = new Tag();
        if (isset($request['is_admin'])) {
            $tag->is_admin = 1;
        }
        $tag->title = $request['title'][getDefaultLocaleId()];
        $tag->save();
        $results = $this->saveTagTranslateFromRequest($request, $tag);

        return $tag;
    }

    /**
     * update tag
     *
     * @param Tag $tag
     * @param array $request
     * @return Tag
     */
    public function update(Tag $tag, array $request): Tag
    {
        $is_admin = null;
        if (isset($request['is_admin'])) {
            $is_admin = 1;
        }
        $tag->is_admin = $is_admin;
        $tag->title = $request['title'][getDefaultLocaleId()];
        $tag->save();
        $results = $this->updateTagTranslateFromRequest($request, $tag);

        return $tag;
    }
    
    /**
     * set tag translate data from request
     *
     * @param array $request
     * @param TagTranslate $tagTranslate
     */
    private function saveTagTranslateFromRequest(array $request, Tag $tag) {
        foreach (getLocaleList() as $row) {
            $this->includeTagTranslateArr($request, $tag, $row);
        }
    }

    private function includeTagTranslateArr(array $request, Tag $tag, $row) {
        $brandTranslate = TagTranslate::firstOrNew(['tag_id' => $tag->id, 'language_id' => $row->id ]);
        $brandTranslate->tag_id = $tag->id;
        $brandTranslate->language_id = $row->id;
        $brandTranslate->language_code = $row->code;
        $brandTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        $brandTranslate->status = true;
        $brandTranslate->save();
    }
    
    /**
     * update tag translate data from request
     *
     * @param array $request
     * @param TagTranslate $tagTranslate
     */
    private function updateTagTranslateFromRequest(array $request, Tag $tag) {
        foreach (getLocaleList() as $row) {
            $this->includeTagTranslateArr($request, $tag, $row);
        }
    }
}
