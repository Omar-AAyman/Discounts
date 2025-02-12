<?php

namespace App\Services\SellerTypes;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerType3Strategy implements SellerTypeStrategy
{
    public function handle(Request $request, Store $store)
    {
        foreach ($request->products as $product) {
            Product::create([
                'store_id' => $store->id,
                'name' => $product['name'],
                'price' => $product['price_before_discount'],
                'discount_percentage' => $product['discount_percentage'] ?? null,
                'discount_amount' => $product['discount_amount'] ?? null,
                'is_online' => true,
            ]);
        }

        // Handle product deletions
        if (!empty($request->deleted_products_ids) && is_array($request->deleted_products_ids)) {
            Product::whereIn('id', $request->deleted_products_ids)->where('store_id', $store->id)->delete();
        }

    }
}
