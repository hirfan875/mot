<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->product_translates ? $this->product_translates->title : $this->title,
            'seller_name' => $this->store != null ? isset($this->store->store_profile_translates) ? $this->store->store_profile_translates->name : $this->store->name : null,
            'price' => (double)$this->price,
            'promo_price' => (double)$this->promo_price,
            'image' => $this->image,
            'stock' => $this->getTotalStock() == null ? 0 : $this->getTotalStock(),
            'average_rating' => $this->rating,
            'total_ratings' => $this->rating_count,
            'is_wishlist' => $this->IsWishlist(),
            'is_variable' => !$this->isSimple() && !$this->isBundle(),
            'type' => $this->type,
            'gallery' => $this->gallery->count() > 0 ? $this->gallery : [],
            'is_sold_out' => $this->soldOut(),
            'is_top' => $this->isTop(),
            'is_new' => $this->isNew(),
            'is_sale' => $this->isSale(),
            'created_at' => $this->created_at,
        ];
    }
}
