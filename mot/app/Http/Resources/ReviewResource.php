<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'comment' => $this->comment,
            'customer_name' => $this->customer->name,
            'customer_image' => $this->customer->image,
            'rating' => $this->rating,
            'gallery' => $this->gallery,
            'date' => getFormatedDate($this->created_at),
        ];
    }
}
