<?php

namespace App\Http\Controllers\API;

use App\Helpers\SubscriptionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getAllProducts()
    {
        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds; // Return response if unauthorized or no subscription
        }

        // Fetch all products with their related store, filtering out products without a store
        $products = Product::with(['store.user'])
            ->whereIn('store_id', $storeIds)
            ->whereNull('offer_id')
            ->where('is_excluded_from_discount', 0)
            ->whereHas('store', function ($query) {
                $query->where('is_online', 1)
                ->where('status', '!=', 'pending');
            })
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }

    public function getProductDetails($id)
    {
        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds; // Return response if unauthorized or no subscription
        }

        $product = Product::with('store')
            ->where('id', $id)
            ->whereIn('store_id', $storeIds)
            ->whereHas('store', function ($query) {
                $query->where('is_online', 1)
                ->where('status', '!=', 'pending');
            })
            ->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found or does not have a store',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product,
        ], 200);
    }
}
