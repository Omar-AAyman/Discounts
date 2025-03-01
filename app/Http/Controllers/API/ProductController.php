<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getAllProducts()
    {
        // Fetch all products with their related store, filtering out products without a store
        $products = Product::with(['store.user'])->has('store')->get();
        return response()->json([
            'status' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products
        ],200);

    }

    public function getProductDetails($id)
    {
        // Fetch a specific product by ID with its related store, ensuring it has a store
        $product = Product::with('store')->has('store')->find($id);

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
        ],200);
    }
}
