<?php

namespace App\Http\Controllers\API\Sellers;

use App\Http\Controllers\Controller;
use App\Models\DiscountRequest;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class DiscountRequestController extends Controller
{
    public function getCurrentStoreDiscount(Request $request)
    {
        $userId = auth()->id(); // Get authenticated user


        // Fetch the store
        $store = Store::where('user_id', '=', $userId)->first();
        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'No related store found.',
            ], 404);
        }

        // ✅ If it's a store, check if the user is its owner
        if ($store->user_id !== $userId) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to access this store discount.',
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Current discount retrieved successfully.',
            'discount_percentage' => number_format($store->discount_percentage ?? 0, 2) . '%',
        ]);
    }

    public function requestStoreDiscountUpdate(Request $request)
    {
        $request->validate([
            'new_discount_percentage' => 'required|numeric|min:0|max:100'
        ]);

        // Get the authenticated user (store owner or product manager)
        $userId = auth()->id();

        // Fetch the store
        $store = Store::where('user_id', '=', $userId)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'No related store found.',
            ], 200);
        }

        // ✅ If it's a store, check if the user is its owner
            if ($store->user_id !== $userId) { // Assuming 'owner_id' is the column for the user who owns the store
                return response()->json([
                    'status' => false,
                    'message' => 'You are not authorized to request a discount for this store.',
                ], 200);
            }

        // Check if a pending request already exists
        if (DiscountRequest::where('store_id', $store->id)
            ->where('status', 'pending')
            ->exists()
        ) {
            return response()->json([
                'status' => false,
                'message' => 'A request is already pending for approval.',
                'requested_discount_percentage' => null,
            ], 200);
        }

        // Create a new discount request
        $discountRequest = DiscountRequest::create([
            'store_id' => $store->id,
            'old_discount_percentage' => $store->discount_percentage ?? 0,
            'requested_discount_percentage' => $request->new_discount_percentage,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Discount change request submitted for approval.',
            'requested_discount_percentage' => number_format($request->new_discount_percentage, 2) . '%',
        ]);
    }
}
