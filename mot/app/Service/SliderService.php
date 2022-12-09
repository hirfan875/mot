<?php

namespace App\Service;

use App\Models\Slider;
use Illuminate\Support\Collection;
use App\Models\SliderTranslate;

class SliderService {

    /**
     * create new slider
     *
     * @param array $request
     * @return Slider
     */
    public function create(array $request): Slider 
    {
        $slider = new Slider();
        $input['image'] = $request['image'][getDefaultLocaleId()];
        // upload new file
        if (isset($request['image'][getDefaultLocaleId()])) {
            $slider->image = Media::upload($request['image'][getDefaultLocaleId()], true, true, 'slider');
        }
        $slider->sort_order = $this->getSortOrderNumber();
        $slider->button_text = $request['button_text'][getDefaultLocaleId()];
        $slider->button_url = $request['button_url'];
        $slider->save();
        $results = $this->saveSliderTranslateFromRequest($request, $slider);

        return $slider;
    }

    /**
     * update slider
     *
     * @param Slider $slider
     * @param array $request
     * @return Slider
     */
    public function update(Slider $slider, array $request): Slider 
    {
        
        // upload new file
        if (isset($request['image'][getDefaultLocaleId()])) {
            $slider->image = Media::upload($request['image'][getDefaultLocaleId()], true, true, 'slider');
        }
        $slider->button_text = $request['button_text'][getDefaultLocaleId()];
        $slider->button_url = $request['button_url'];
        $slider->save();

        $results = $this->updateSliderTranslateFromRequest($request, $slider);

        return $slider;
    }

    /**
     * get sort order number
     *
     * @return int
     */
    private function getSortOrderNumber(): int 
    {
        return Slider::count();
    }

    /**
     * get all slider banners
     *
     * @return Collection
     */
    public function getSlider(): Collection 
    {
        return Slider::whereStatus(true)->orderBy('sort_order')->get();
    }

    /**
     * @return Collection
     */
    public function getHomePageSliders() {
        return Slider::with('slider_translates')->whereStatus(true)->orderBy('sort_order', 'asc')->get();
    }

    /**
     * set banner translate data from request
     *
     * @param array $request
     * @param SliderTranslate $sliderTranslate
     */
    private function saveSliderTranslateFromRequest(array $request, Slider $slider) 
    {
        foreach (getLocaleList() as $row) {
            $this->includeSliderTranslateArr($request, $slider, $row);
        }
    }

    private function includeSliderTranslateArr(array $request, Slider $slider, $row) 
    {
        $sliderTranslate = SliderTranslate::firstOrNew(['slider_id' => $slider->id, 'language_id' => $row->id]);
        $sliderTranslate->slider_id = $slider->id;
        $sliderTranslate->language_id = $row->id;
        if (isset($request['image'][$row->id])) {
            $sliderTranslate->image = Media::upload($request['image'][$row->id], true, true, 'slider');
        } else {
            $sliderTranslate->image = Media::upload($request['image'][getDefaultLocaleId()], true, true, 'slider');
        }
        $sliderTranslate->button_text = $request['button_text'][$row->id] ? $request['button_text'][$row->id] : $request['button_text'][getDefaultLocaleId()];
        $sliderTranslate->button_url = $request['button_url'];
        $sliderTranslate->status = true;
        $sliderTranslate->save();
    }

    /**
     * update banner translate data from request
     *
     * @param array $request
     * @param SliderTranslate $sliderTranslate
     */
    private function updateSliderTranslateFromRequest(array $request, Slider $slider) 
    {
        foreach (getLocaleList() as $row) {
            $this->updateSliderTranslateArr($request, $slider, $row);
        }
    }
    
    private function updateSliderTranslateArr(array $request, Slider $slider, $row) 
    {
        $sliderTranslate = SliderTranslate::firstOrNew(['slider_id' => $slider->id, 'language_id' => $row->id]);
        $sliderTranslate->slider_id = $slider->id;
        $sliderTranslate->language_id = $row->id;
        if (isset($request['image'][$row->id])) {
            $sliderTranslate->image = Media::upload($request['image'][$row->id], true, true, 'slider');
        }
        $sliderTranslate->button_text = $request['button_text'][$row->id] ? $request['button_text'][$row->id] : $request['button_text'][getDefaultLocaleId()];
        $sliderTranslate->button_url = $request['button_url'];
        $sliderTranslate->status = true;
        $sliderTranslate->save();
    }

}
