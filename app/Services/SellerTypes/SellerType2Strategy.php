<?php

namespace App\Services\SellerTypes;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class SellerType2Strategy implements SellerTypeStrategy
{

    public function handle(Request $request, Store $store)
    {
        // Apply store-wide dsscount
        Store::where('id', $store->id)->update([
            'discount_percentage' => $request->discount_percentage,
        ]);

        // Exclude specific items from discount and create if they don't exist
        foreach ($request->excluded_products as $excludedProduct) {
            Product::Create([
                'store_id' => $store->id,
                'name' => $excludedProduct['name'], // Search condition (name)
                'price' => $excludedProduct['price_before_discount'] ?? null,
                'discount_percentage' => $excludedProduct['discount_percentage'] ?? null,
                'discount_amount' => $excludedProduct['discount_amount'] ?? null,
                'is_excluded_from_discount' => true // Fields to set if creating
            ]);
        }

        // Handle product deletions
        if (!empty($request->deleted_products_ids) && is_array($request->deleted_products_ids)) {
            Product::whereIn('id', $request->deleted_products_ids)->where('store_id', $store->id)->delete();
        }
    }
}
