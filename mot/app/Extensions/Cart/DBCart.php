<?php


namespace App\Extensions\Cart;

use App\Models\Cart;
use Darryldecode\Cart\CartCollection;


class DBCart
{

    public function has($key)
    {
        return Cart::find($key);
    }

    public function get($key)
    {
        if(! $this->has($key))  {
            return [];
        }
        return new CartCollection(Cart::find($key)->cart_data);
    }

    public function put($key, $value)
    {
        if($row = Cart::find($key)) {
            // update
            $row->cart_data = $value;
            $row->save();
            return ;
        }
        Cart::create([
            'id' => $key,
            'cart_data' => $value
        ]);
    }
}
