<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class UniqueCategory implements Rule
{
    protected $parent_id;
    protected $category_id;

    /**
     * Create a new rule instance.
     *
     * @param int|null $store_id
     * @param int $category_id
     * @return void
     */
    public function __construct($parent_id = null, $category_id = 0)
    {
        $this->parent_id = empty($parent_id) ? null : $parent_id;
        $this->category_id = $category_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Category::whereParentId($this->parent_id)->whereTitle($value)->where('id', '<>', $this->category_id)->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute has already been taken.');
    }
}
