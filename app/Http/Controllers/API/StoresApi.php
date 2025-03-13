<?php

namespace App\Http\Controllers\API;

use App\Helpers\SubscriptionHelper;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Store;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class StoresApi extends Controller
{
    public function getSellersWithStores()
    {
        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds; // Return response if unauthorized or no subscription
        }

        // Fetch stores where the store is online and include related data
        $stores = Store::whereIn('id', $storeIds)
            ->where('is_online', 1)
            ->where('status', '!=', 'pending')
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

        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds;
        }

        $storesWithOffers = Store::whereIn('id', $storeIds)->has('offers')->get();
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
        if (!$store) {
            return response(['message' => 'Store doesn\'t exist']);
        }

        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds;
        }

        if (!$storeIds->contains($store->id)) {
            return response(['message' => 'This store is not included in your subscription'], 403);
        }

        return response(['store' => $store]);
    }

    public function mostPopularStores()
    {
        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds;
        }

        $stores = Store::whereIn('id', $storeIds)->where('is_most_popular', 1)->get();

        if ($stores->isEmpty()) {
            return response(['message' => 'There are no most popular providers defined']);
        }

        return response(['most_popular_providers' => $stores]);
    }

    public function filterStores(Request $request)
    {
        $data = $request->validate([
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds;
        }

        $stores = Store::whereIn('id', $storeIds)
            ->where('city', $data['city'])
            ->where('country', $data['country'])
            ->get();

        if ($stores->isEmpty()) {
            return response(['message' => 'No matched stores']);
        }

        return response(['stores' => $stores]);
    }

    public function getUserSubscribedSections()
    {
        $sections = SubscriptionHelper::getUserSubscribedSections();

        if ($sections instanceof \Illuminate\Http\JsonResponse) {
            return $sections;
        }

        return response()->json([
            'status' => true,
            'message' => 'User subscribed sections retrieved successfully',
            'data' => $sections,
        ], 200);
    }
}
