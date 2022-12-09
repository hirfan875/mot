<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $today_date = now()->toDateTimeString();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->coupon_code,
            'discount' => $this->discount,
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image' => $this->image
        ];
    }
}
