<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoProductFormFields extends Component {

    public $meta_title;
    public $meta_desc;
    public $meta_keyword;
    public $product_translate;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($row = null) {
        $this->product_translate = $row != null ? $row->product_translate : '' ;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.admin.seo-product-form-fields');
    }

}
