<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'discount_percentage' => $this->discount_percentage ? $this->discount_percentage . '%' : null,
            'discount_amount' => $this->discount_amount ? $this->discount_amount : null,
            'price_before_discount' => $this->price_before_discount ? $this->price_before_discount : null,
            'price_after_discount' => $this->discount_percentage
                ? $this->price_before_discount - ($this->price_before_discount * ($this->discount_percentage / 100))
                : $this->price_before_discount - $this->discount_amount,
            'bg_img' => $this->bg_img,
            'is_online' => $this->is_online,
            'products' => ProductResource::collection($this->whenLoaded('products')), // Assuming a relationship exists
        ];
    }
}
