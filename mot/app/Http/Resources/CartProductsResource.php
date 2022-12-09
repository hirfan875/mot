<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $title = $this->product->product_translates ? $this->product->product_translates->title : $this->product->title;
//        $product = $this->product;
        if ($this->product->parent != null) {
            $title = $this->product->parent->product_translates ? $this->product->parent->product_translates->title : $this->product->parent->title;
//            $product = $this->product->parent;
        }

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $title,
            'quantity' => $this->quantity,
            'stock' => $this->product->stock,
            'slug' => $this->product->slug,
            'parent_id' => $this->product->parent_id,
            'unit_price' => (double)$this->unit_price,
            'delivery_fee' => (double)$this->delivery_fee,
            'currency_id' => $this->currency_id,
            'seller_name' => $this->product->store != null ? isset($this->product->store->store_profile_translates) ? $this->product->store->store_profile_translates->name : $this->product->store->name : null,
            'seller_slug' => $this->product->store != null ? $this->product->store->slug : null,
            'image' => $this::getProductImage($this->product),
            'attribute_name' => getAttributeWithOption($this->product),
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * @param $product
     * @return mixed
     */
    private static function getProductImage($product)
    {
        $image = null;
        if ($product->parent_id != null) {
            $image = $product->image;
            if ($image == null) {
                $image = $product->parent->image;
            }
            if ($image == null) {
                if ($product->parent->gallery->count() > 0) {
                    $image = $product->parent->gallery[0]->image;
                }
            }
        } else {
            $image = $product->image;

            if ($image == null) {
                if ($product->gallery->count() > 0) {
                    $image = $product->gallery[0]->image;
                }
            }
        }

        return $image;
    }
}
