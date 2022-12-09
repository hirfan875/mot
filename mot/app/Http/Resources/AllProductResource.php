<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AllProductResource extends JsonResource
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
            'title' => $this->product_translates ? $this->product_translates->title : $this->title,
            'slug' => $this->slug,
        ];
    }
}
