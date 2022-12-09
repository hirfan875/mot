<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecentViewedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(isset($this->product)) {
        return [
            'id' => isset($this->product) ? $this->product->id : null,
            'name' => $this->product->product_translates ? $this->product->product_translates->title : $this->product->title,
            'seller_name' => $this->product->store != null ? isset($this->product->store->store_profile_translates) ? $this->product->store->store_profile_translates->name : $this->product->store->name : null,
            'price' => (double)$this->product->price,
            'promo_price' => (double)$this->product->promo_price,
            'image' => $this->product->image,
            'stock' => $this->product->getTotalStock() == null ? 0 : $this->product->getTotalStock(),
            'average_rating' => $this->product->rating,
            'total_ratings' => $this->product->rating_count,
            'is_wishlist' => $this->product->IsWishlist(),
            'is_variable' => !$this->product->isSimple() && !$this->product->isBundle(),
            'type' => $this->product->type,
            'gallery' => $this->product->gallery->count() > 0 ? $this->product->gallery : [],
            'is_sold_out' => $this->product->soldOut(),
            'is_top' => $this->product->isTop(),
            'is_new' => $this->product->isNew(),
            'is_sale' => $this->product->isSale(),
            'created_at' => getFormatedDate($this->created_at),
        ];
        }
    }
}
