<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->store_profile_translates ? $this->store_profile_translates->name : $this->name,
            'slug' => $this->slug,
            'logo' => $this->store_data->logo,
            'banner' => $this->store_data->banner,
            'rating_count' => $this->lifetimeRatingCount(),
            'rating' => $this->rating,
        ];
    }
}
