<?php

namespace App\Service;

use App\Models\SponsorCategory;
use App\Models\SponsorSection;
use App\Models\SponsorCategoriesTranslate;
use Illuminate\Support\Facades\Storage;

class SponsorSectionService
{
    /**
     * create new section
     *
     * @param array $request
     * @return SponsorSection
     */
    public function create(array $request): SponsorSection
    {
        $section = new SponsorSection();
        $section->title = $request['title'];
        $section->save();

        $homepageSectionsService = new HomepageSectionsService();
        $homepageSectionsService->set_sort_order($section);
        
        $this->saveBoxes($request, $section);

        return $section;
    }

    /**
     * update section
     *
     * @param SponsorSection $section
     * @param array $request
     * @return SponsorSection
     */
    public function update(SponsorSection $section, array $request): SponsorSection
    {
        $section->title = $request['title'];
        $section->save();
       
        $this->saveBoxes($request, $section);

        return $section;
    }

    /**
     * save category boxes
     *
     * @param array $request
     * @param SponsorSection $section
     * @return void
     */
    private function saveBoxes(array $request, SponsorSection $section)
    {
//         dd($request['categories']);
        foreach ($request['categories'] as $key => $box) {
//            dd($key , $box);
            $category = new SponsorCategory();
            if (isset($box['id']) && !empty($box['id'])) {
                $category = SponsorCategory::find($box['id']);
                $file_name = $category->image;
            }
            
            $category->sponsor_section_id = $section->id;
            $category->title = $box['title'][getDefaultLocaleId()];
            $category->button_text = $box['button_text'][getDefaultLocaleId()];
            $category->button_url = $box['button_url'];

            // delete old image
//            if (isset($box['image'][getDefaultLocaleId()]) || $request['remove_categories'][$key]['image'][getDefaultLocaleId()] === 'Yes') {
//                $file_name = Media::delete($category->image);
//            }
            
            // upload new image
            if (isset($box['image'][getDefaultLocaleId()])) {
                $file_name = Media::upload($box['image'][getDefaultLocaleId()], true, true, 'sponsor_category');

                // save crop image
                if (isset($request['new_crop_categories'][$key]['image'][getDefaultLocaleId()]) && !empty($request['new_crop_categories'][$key]['image'][getDefaultLocaleId()])) {

                    $size = 'sponsor_category';
                    Media::createDirectory($size);
                    $explodeImage = explode(";base64,", $request['new_crop_categories'][$key]['image'][getDefaultLocaleId()]);
                    $new_image = base64_decode($explodeImage[1]);
                    $imagePath = $size . DIRECTORY_SEPARATOR . $file_name;
                    Storage::put($imagePath, $new_image);
                }
                $category->image = $file_name;
            }

            $category->save();
            
            $results = $this->saveSponsorCategoryTranslateFromRequest($box, $category);
        }
    }

    /**
     * delete section
     *
     * @param SponsorSection $section
     * @return void
     */
    public function delete(SponsorSection $section)
    {
        $sort_order = $section->sort->sort_order;
        $section->sort->delete();

        // delete images
        $section->load(['categories']);
        foreach ($section->categories as $row) {
            Media::delete($row->image);
        }

        // delete section
        $section->delete();

        $homepageSectionsService = new HomepageSectionsService();
        $homepageSectionsService->decrement_sort_order($sort_order);
    }
    
    /**
     * set banner translate data from request
     *
     * @param array $request
     * @param SponsorCategory $sponsorCategory
     */
    private function saveSponsorCategoryTranslateFromRequest(array $request, SponsorCategory $sponsorCategory) {
        foreach (getLocaleList() as $row) {
            $this->includeSponsorCategoryTranslateArr($request, $sponsorCategory, $row);
        }
    }

    private function includeSponsorCategoryTranslateArr(array $request, SponsorCategory $sponsorCategory, $row) {
        
        $sponsorCategoryTranslate = SponsorCategoriesTranslate::firstOrNew(['sponsor_category_id' => $sponsorCategory->id, 'language_id' => $row->id ]);
        $sponsorCategoryTranslate->sponsor_category_id = $sponsorCategory->id;
        $sponsorCategoryTranslate->language_id = $row->id;
        $sponsorCategoryTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        
        if (isset($request['image'][$row->id])) {
            $sponsorCategoryTranslate->image = Media::upload($request['image'][$row->id], true, true, 'image');
        } 
        $sponsorCategoryTranslate->button_text = $request['button_text'][$row->id] ? $request['button_text'][$row->id] : $request['button_text'][getDefaultLocaleId()];
        $sponsorCategoryTranslate->status = true;
        $sponsorCategoryTranslate->save();
    }
    
    /**
     * update banner translate data from request
     *
     * @param array $request
     * @param BannerTranslate $bannerTranslate
     */
    private function updateSponsorCategoryTranslateFromRequest(array $request, SponsorCategory $sponsorCategory) {
        foreach (getLocaleList() as $row) {
            $this->updateSponsorCategoryTranslateArr($request, $sponsorCategory, $row);
        }
    }
    
    private function updateSponsorCategoryTranslateArr(array $request, SponsorCategory $sponsorCategory, $row) {
        $sponsorCategoryTranslate = SponsorCategoriesTranslate::firstOrNew(['banner_id' => $sponsorCategory->id, 'language_id' => $row->id ]);
        $sponsorCategoryTranslate->sponsor_category_id = $sponsorCategory->id;
        $sponsorCategoryTranslate->language_id = $row->id;
        $sponsorCategoryTranslate->title = $request['title'][$row->id] ? $request['title'][$row->id] : $request['title'][getDefaultLocaleId()];
        if (isset($request['image'][$row->id])) {
            $sponsorCategoryTranslate->image = Media::upload($request['image'][$row->id], true, true, 'image');
        }
        $sponsorCategoryTranslate->button_text = $request['button_text'][$row->id] ? $request['button_text'][$row->id] : $request['button_text'][getDefaultLocaleId()];
        $sponsorCategoryTranslate->status = true;
        $sponsorCategoryTranslate->save();
    }
}
