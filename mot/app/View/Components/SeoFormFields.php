<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoFormFields extends Component
{
    public $meta_title;
    public $meta_desc;
    public $meta_keyword;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($row=null)
    {
        $this->meta_title = ( $row != null ? $row->meta_title : '' );
        $this->meta_desc = ( $row != null ? $row->meta_desc : '' );
        $this->meta_keyword = ( $row != null ? $row->meta_keyword : '' );
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.admin.seo-form-fields');
    }
}
