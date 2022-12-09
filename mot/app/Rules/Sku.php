<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class Sku implements Rule
{
    protected $store_id;
    protected $product_id;

    /**
     * Create a new rule instance.
     *
     * @param integer $store_id
     * @param int $product_id
     * @return void
     */
    public function __construct($store_id = 0, $product_id = 0)
    {
        $this->store_id = $store_id;
        $this->product_id = $product_id;
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
//        return Product::whereStoreId($this->store_id)->whereSku($value)->where('id', '<>', $this->product_id)->doesntExist();
        return Product::whereSku($value)->where('id', '<>', $this->product_id)->doesntExist();
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
