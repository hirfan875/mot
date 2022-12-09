<?php

namespace App\Service;

use App\Models\Attribute;
use App\Models\AttributeTranslate;

class AttributeService
{
    /**
     * create new attribute
     *
     * @param array $request
     * @param int|null $parent_id
     * @return Attribute
     */
    public function create(array $request, int $parent_id = null): Attribute
    {
        $attribute = new Attribute();
        if ( !empty($parent_id) ) {
            $attribute->parent_id = $parent_id;
        }
        $attribute->title = $request['title'][getDefaultLocaleId()];
        if ( isset($request['type']) ) {
            $attribute->type = $request['type'];
        }
        if ( isset($request['code']) ) {
            $attribute->code = $request['code'];
        }
        $attribute->sort_order = $this->getSortOrderNumber($parent_id);
        $attribute->save();

        $results = $this->saveAttributeTranslateFromRequest($request, $attribute);

        return $attribute;
    }

    /**
     * update attribute
     *
     * @param Attribute $attribute
     * @param array $request
     * @return Attribute
     */
    public function update(Attribute $attribute, array $request): Attribute
    {
        $attribute->title = $request['title'][getDefaultLocaleId()];
        if ( isset($request['type']) ) {
            $attribute->type = $request['type'];
        }
        if ( isset($request['code']) ) {
            $attribute->code = $request['code'];
        }
        $attribute->save();
        $results = $this->updateAttributeTranslateFromRequest($request, $attribute);

        return $attribute;
    }

    /**
     * get sort order number
     *
     * @param int/null $parent_id
     * @return int
     */
    private function getSortOrderNumber(int $parent_id = null): int
    {
        return Attribute::whereParentId($parent_id)->count();
    }

    public function getAttributeID(int $data): Attribute
    {
        $attribute = new Attribute();
        if (isset($data) && !empty($data)) {
            $attribute =  Attribute::with('parent')->find($data);
        }
        return $attribute;
    }

    public function getAttributeTitleByID(array $data): array
    {
        $attributeTitle = [];
        if (isset($data) && !empty($data)) {
            $attributeTitle =  Attribute::whereIn('id', $data)->pluck('title')->toArray();
        }
        return $attributeTitle;
    }

    /**
     * set brand translate data from request
     *
     * @param array $request
     * @param Attribute $attribute
     */
    private function saveAttributeTranslateFromRequest(array $request, Attribute $attribute)
    {
        foreach (getLocaleList() as $row) {
            $this->includeAttributeTranslateArr($request, $attribute, $row);
        }
    }

    private function includeAttributeTranslateArr(array $request, Attribute $attribute, $row)
    {
        $attributeTranslate = AttributeTranslate::firstOrNew(['attribute_id' => $attribute->id, 'language_id' => $row->id ]);
        $attributeTranslate->attribute_id = $attribute->id;
        $attributeTranslate->language_id = $row->id;
        $attributeTranslate->language_code = $row->code;
        $attributeTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        $attributeTranslate->status = true;
        $attributeTranslate->save();
    }

    /**
     * update brand translate data from request
     *
     * @param array $request
     * @param Attribute $attribute
     */
    private function updateAttributeTranslateFromRequest(array $request, Attribute $attribute)
    {
        foreach (getLocaleList() as $row) {
            $this->includeAttributeTranslateArr($request, $attribute, $row);
        }
    }

    /**
     * @param $title
     * @return mixed
     */
    public function getAttributeByTitle($title)
    {
        return Attribute::where('title', $title)->whereNull('parent_id')->first();
    }

    /**
     * @param $title
     * @return mixed
     */
    public function getOptionByTitle($title)
    {
        return Attribute::where('title', $title)->whereNotNull('parent_id')->first();
    }

    /**
     * @return mixed
     */
    public function getAllGroupedByTitle()
    {
        return Attribute::with('options')->whereNull('parent_id')->get()->groupBy('title');
    }
}
