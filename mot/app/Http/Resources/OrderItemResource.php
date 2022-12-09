<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
        if ($this->product->parent != null) {
            $title = $this->product->parent->product_translates ? $this->product->parent->product_translates->title : $this->product->parent->title;
        }

        return [
            'id' => $this->id,
            'status' => $this->status,
            'quantity' => $this->quantity,
            'store_id' => $this->product->store->id,
            'store_name' => $this->product->store->store_profile_translates ? $this->product->store->store_profile_translates->name : $this->product->store->name,
            'store_slug' => $this->product->store->slug,
            'product_name' => $title,
            'product_slug' => $this->product->slug,
            'sku' => $this->product->sku,
            'attribute_name' => getAttributeWithOption($this->product),
            'product_price' => (double)$this::getTotalAttribute(),
            'product_image' => $this::getProductImage($this->product),
            'store_order_id' => $this->store_order->id,
            'is_able_to_review' => $this->isAbleToReview(),
        ];
    }

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
