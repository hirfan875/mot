<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashDealResource extends JsonResource
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
            'slug' => $this->product->slug,
            'product_id' => $this->product_id,
            'name' => $this->product != null ? isset($this->product->product_translates) ? $this->product->product_translates->title : $this->product->title : null,
            'seller_name' => $this->product->store != null ? isset($this->product->store->store_profile_translates) ? $this->product->store->store_profile_translates->name : $this->product->store->name : null,
            'price' => (double)$this->product->price,
            'promo_price' => (double)$this->product->promo_price,
            'image' => $this->image,
            'discount' => $this->discount,
            'ending_att' => $this->ending_at,
//            'ending_at' => \Carbon\Carbon::createFromTimestampUTC($this->ending_at)->diffInSeconds(),
            'ending_at' => $this->ending_at->isFuture() ? \Carbon\Carbon::parse($this->ending_at)->diffInSeconds() : 0,
        ];
    }
}
