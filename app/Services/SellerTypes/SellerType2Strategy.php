<?php

namespace App\Services\SellerTypes;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class SellerType2Strategy implements SellerTypeStrategy
{

    public function handle(Request $request, Store $store)
    {
        // Apply store-wide discount
        Store::where('id', $store->id)->update([
            'discount_percentage' => $request->discount_percentage,
        ]);

        // Step 1: Collect existing product IDs
        $existingProductIds = Product::where('store_id', $store->id)->pluck('id')->toArray();

        // Step 2: Prepare new excluded product IDs
        $newExcludedProductIds = [];

        // Add or update the products received in the request
        foreach ($request->excluded_products as $excludedProduct) {
            if (isset($excludedProduct['id'])) {
                // Update existing product name if an ID is provided
                $existingProduct = Product::find($excludedProduct['id']);
                if ($existingProduct) {
                    $existingProduct->name = $excludedProduct['name'];
                    $existingProduct->save();
                }
                // Add it to the new list
                $newExcludedProductIds[] = $excludedProduct['id'];
            } else {
                // Create a new product if no ID is provided
                $newProduct = Product::create([
                    'store_id' => $store->id,
                    'name' => $excludedProduct['name'],
                    'price' => $excludedProduct['price_before_discount'] ?? null,
                    'discount_percentage' => $excludedProduct['discount_percentage'] ?? null,
                    'discount_amount' => $excludedProduct['discount_amount'] ?? null,
                    'is_excluded_from_discount' => true // Fields to set if creating
                ]);
                $newExcludedProductIds[] = $newProduct->id; // Add the new product ID to the list
            }
        }

        // Step 3: Remove old products that are not in the new list
        foreach ($existingProductIds as $existingId) {
            if (!in_array($existingId, $newExcludedProductIds)) {
                // Delete the product if it's not in the new list
                Product::where('id', $existingId)->delete();
            }
        }
    }
}
