<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->category_translates ? $this->category_translates->title : $this->title,
            'image' => $this->image,
            'slug' => $this->slug,
            'banner_image' => $this->banner,
        ];
    }
}
