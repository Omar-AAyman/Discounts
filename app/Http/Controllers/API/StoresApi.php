<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class StoresApi extends Controller
{
    public function getSellersWithStores()
    {
        // Fetch stores where the store is online and include related data
        $stores = Store::where('is_online', 1)
            ->with(['branches', 'products', 'excludedProducts', 'user'])
            ->get()
            ->groupBy('user_id') // Group by user_id (seller)
            ->map(function ($stores, $userId) {
                $user = $stores->first()->user; // Get the user from the first store
                $store = $stores->first(); // Get only the first store for this seller

                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'uuid' => $user->uuid,
                    'email_verified_at' => $user->email_verified_at,
                    'type' => $user->type,
                    'seller_type_id' => $user->seller_type_id,
                    'is_online' => $user->is_online,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'phone2' => $user->phone2,
                    'facebook' => $user->facebook,
                    'instagram' => $user->instagram,
                    'push_notifications_enabled' => $user->push_notifications_enabled,
                    'is_sponser' => $user->is_sponser,
                    'points' => $user->points,
                    'is_admin' => $user->is_admin,
                    'img' => $user->img,
                    'city' => $user->city,
                    'city_name' => $user->city_name,
                    'city_name_ar' => $user->city_name_ar,
                    'country' => $user->country,
                    'country_name' => $user->country_name,
                    'country_name_ar' => $user->country_name_ar,
                    'fcm_token' => $user->fcm_token,
                    'is_seller' => $user->is_seller,
                    'fullname' => $user->first_name . ' ' . $user->last_name,
                    'store' => $store->makeHidden('user')
                ];
            })
            ->values(); // Reset array keys


        return response()->json(['sellers' => $stores]);
    }

    public function storesWithOffers()
    {

        $storesWithOffers = Store::has('offers')->get();

        return response(['stores with offers' => $storesWithOffers]);
    }
    public function storesWithProducts()
    {

        $storesWithProducts = Store::has('products')->get();

        return response(['status' => true, 'data' => $storesWithProducts], 200);
    }

    public function store(Request $request)
    {
        $request->validate(['uuid' => 'required']);
        $uuid = $request->uuid;
        $store = Store::where('uuid', $uuid)->first();
        if ($store) {

            return response(['store' => $store]);
        } else {
            return response(['message' => 'store doesn\'t exist']);
        }
    }

    public function mostPopularStores()
    {
        $stores = Store::where('is_most_popular', 1)->get();
        if (count($stores) > 0) {
            return response(['most popular providers' => $stores]);
        } else {
            return response(['message' => 'there\'s no most popular providers defined']);
        }
    }

    public function filterStores(Request $request)
    {
        $data = $request->validate([
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        $stores = Store::where('city', $data['city'])->where('country', $data['country'])->get();
        if (count($stores) > 0) {
            return response(['stores' => $stores]);
        } else {
            return response(['message' => 'no matched stores']);
        }
    }

    // public function applyDiscount($storeId)
    // {
    //     $store = Store::findOrFail($storeId);

    //     // Assuming 'discount_percentage' is a column in the stores table
    //     $discount = $store->discount_percentage ? $store->discount_percentage.'%' : '0%';

    //     return response()->json([
    //         'message' => 'Discount applied successfully!',
    //         'store_id' => $store->id,
    //         'discount' => $discount
    //     ]);
    // }

    public function showDiscount($uuid)
    {
        $store = Store::where('uuid', $uuid)->with('products', 'excludedProducts', 'offers')->firstOrFail();
        // dd($store);
        return response()->json([
            'message' => 'Store data retrieved successfully!',
            'store' => $store,
        ]);
        // ... rest of your logic
    }
}
