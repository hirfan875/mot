<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'store_id' => $this->store_id,
            'currency' => $this->order->currency != null ? $this->order->currency->code : null,
            'order_number' => $this->order_number,
            'status' => $this->getStatus(),
            'total' => (double)$this->total,
            'order_date' => getFormatedDate($this->order->order_date),
            'store_name' => $this->seller->store_profile_translates ? $this->seller->store_profile_translates->name : $this->seller->name,
            'items' => OrderItemResource::collection($this->order_items),
        ];
    }
}
