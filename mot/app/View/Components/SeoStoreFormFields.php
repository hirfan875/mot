<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoStoreFormFields extends Component {

    public $meta_title;
    public $meta_desc;
    public $meta_keyword;
    public $store_profile_translate;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($row = null) {
        $this->store_profile_translate = $row != null ? $row->store->store_profile_translate : '' ;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.admin.seo-store-form-fields');
    }

}
