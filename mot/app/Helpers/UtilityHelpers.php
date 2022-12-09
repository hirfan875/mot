<?php

namespace App\Helpers;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Config;
use App\Models\Attribute;
use App\Models\ProductAttribute;

Class UtilityHelpers {

	public static function getCartSessionId()
	{
		return Session::get('cart-session-id');
	}

	/**
     * generate cart session id.
     *
     * @param  null
     * @return cart session id
    */
	public static function setCartSessionId(){
		$key = 'cart-session-id';
        $cartSessionId = Session::get($key);
        if (empty($cartSessionId)) {
            $cartSessionId = uniqid();
            Session::put($key, $cartSessionId);
        }

        return $cartSessionId;
	}
    /**
     * generate cart session id.
     *
     * @param  null
     * @return cart session id
     */
    public static function removeCartSessionId()
    {
        $key = 'cart-session-id';
        Session::forget($key);
    }

    public static function getCdnUrl($path = null)
    {
        $parts = parse_url($path);
        if ('/' !== $parts['path'][0]) {
            $parts['path'] = '/' . $parts['path'];
        }
        return config('app.cdn_url').$parts['path'];
    }

    /**
     * @param Product $product
     * @return array
     */
    public static function getVariationNames(Product $product)
    {
        $attr_options = ProductAttribute::where('variation_id', $product->id)->pluck('option_id')->toArray();
        return Attribute::whereIn('id', $attr_options)->pluck('title')->toArray();
    }
    public static function getCommonWordsArray(){
        return [
            "and", "we", "they", "these", "if", "you", "do", "this", "i", "that"
        ];
    }
}
