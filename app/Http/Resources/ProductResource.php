<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price_before_discount' => $this->price,
            'price_after_discount' => $this->discount_percentage
                ? $this->price - ($this->price * ($this->discount_percentage / 100))
                : $this->price - $this->discount_amount,
            'discount_percentage' => $this->discount_percentage ? $this->discount_percentage . '%' : null,
            'discount_amount' => $this->discount_amount,
            'img' => $this->img,
        ];
    }
}
