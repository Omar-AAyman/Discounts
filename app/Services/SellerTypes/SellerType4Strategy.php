<?php

namespace App\Services\SellerTypes;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class SellerType4Strategy implements SellerTypeStrategy
{
    public function handle(Request $request, Store $store)
    {
        // Step 1: Collect existing product IDs
        $existingProductIds = Product::where('store_id', $store->id)->pluck('id')->toArray();

        // Step 2: Prepare new excluded product IDs
        $newExcludedProductIds = [];

        // Create new products from the request
        foreach ($request->products as $product) {
            if (isset($product['id'])) {
                // Update existing product if an ID is provided
                $existingProduct = Product::find($product['id']);
                if ($existingProduct) {
                    $existingProduct->name = $product['name'];
                    $existingProduct->price = $product['price_before_discount'];
                    $existingProduct->discount_percentage = $product['discount_percentage'] ?? null;
                    $existingProduct->discount_amount = $product['discount_amount'] ?? null;
                    $existingProduct->is_online = true;
                    $existingProduct->save();
                }
                // Add it to the new list
                $newExcludedProductIds[] = $product['id'];
            } else {
                // Create a new product if no ID is provided
                $newProduct = Product::create([
                    'store_id' => $store->id,
                    'name' => $product['name'],
                    'price' => $product['price_before_discount'],
                    'discount_percentage' => $product['discount_percentage'] ?? null,
                    'discount_amount' => $product['discount_amount'] ?? null,
                    'is_online' => true,
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
