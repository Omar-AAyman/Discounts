<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    protected $seller;
    protected $store;

    /**
     * StoreSellerResource constructor.
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->seller = $data['seller'];
        $this->store = $data['store'];
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get products and excluded products from the store
        $products = $this->store->products->where('is_excluded_from_discount', false) ?? [];
        $excludedProducts = $this->store->products->where('is_excluded_from_discount', true) ?? [];

        return [
            'seller' => [
                'id' => $this->seller->id,
                'first_name' => $this->seller->first_name,
                'last_name' => $this->seller->last_name,
                'email' => $this->seller->email,
                'phone' => $this->seller->phone,
                'phone2' => $this->seller->phone2,
                'seller_type_id' => $this->seller->seller_type_id,
                'seller_type_ar_description' => $this->seller->sellerType->ar_description,
                'seller_type_en_description' => $this->seller->sellerType->en_description,
                'city' => $this->seller->city,
                'country' => $this->seller->country,
            ],
            'store' => [
                'id' => $this->store->id ?? null,
                'name' => $this->store->name ?? null,
                'store_img' => $this->store->store_img ?? null,
                'contract_img' => $this->store->contract_img ?? null,
                'description' => $this->store->description ?? null,
                'discount_percentage' => $this->store->discount_percentage ? $this->store->discount_percentage . '%' : null,
                'sector_representative' => $this->store->sector_representative ?? null,
                'sector_qr' =>  $this->store->getSectoreQrAttribute($this->store->sector_qr)?? null,
                'work_hours' => $this->store->work_hours ?? null,
                'work_days' => json_decode($this->store->work_days) ?? [],
                'status' => $this->store->status ?? 'pending',
                'facebook' => $this->store->facebook ?? null,
                'instagram' => $this->store->instagram ?? null,
                'tiktok' => $this->store->tiktok ?? null,

                'products' => $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price_before_discount' => $product->price_before_discount,
                        'discount_percentage' => $product->discount_percentage ? $product->discount_percentage . '%' : null,
                        'discount_amount' => $product->discount_amount,
                    ];
                }) ?? [],
                'excluded_products' => $excludedProducts->map(function ($product) {
                    return [
                        'id' => $product->id?? null,
                        'name' => $product->name,
                        'price_before_discount' => $product->price_before_discount,
                        'discount_percentage' => $product->discount_percentage ? $product->discount_percentage . '%' : null,
                        'discount_amount' => $product->discount_amount,
                    ];
                }) ?? [],
                'branches' => $this->store->branches->map(function ($branch) {
                    return [
                        'id' => $branch->id,
                        'city' => $branch->city,
                        'country' => $branch->country,
                        'status' => $branch->status,
                    ];
                }) ?? [],
            ],
        ];
    }
}
