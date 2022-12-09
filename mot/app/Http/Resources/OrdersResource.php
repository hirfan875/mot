<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
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
            'currency' => $this->currency != null ? $this->currency->code : null,
            'order_number' => $this->order_number,
            'status' => $this->getStatus(),
            'total' => (double)$this->total,
            'quantity' => $this->getTotalQty(),
            'order_date' => getFormatedDate($this->order_date)
        ];
    }
}
