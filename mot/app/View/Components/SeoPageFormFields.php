<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoPageFormFields extends Component {

    public $meta_title;
    public $meta_desc;
    public $meta_keyword;
    public $page_translate;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($row = null) {
        $this->page_translate = $row != null ? $row->page_translate : '' ;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.admin.seo-page-form-fields');
    }

}
