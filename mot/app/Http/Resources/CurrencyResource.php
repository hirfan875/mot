<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
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
            'is_default' => $this->is_default,
            'title' => $this->title,
            'base_rate' => (double)$this->base_rate,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'thousand_separator' => $this->thousand_separator,
            'decimal_separator' => $this->decimal_separator,
            'value' => $this->emoji_uc,
        ];
    }
}
