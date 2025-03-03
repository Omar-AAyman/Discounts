<?php

namespace App\Helpers;

use App\Models\Subscription;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class SubscriptionHelper
{
    public static function getUserSubscribedStoreIds()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = self::getActiveSubscription($user->id);

        if (!$subscription) {
            return response()->json([
                'status' => false,
                'message' => 'No active subscription found',
                'data' => []
            ], 403);
        }

        // Fetch all store IDs related to the user's subscribed package sections
        $storeIds = $subscription->package->sections()
            ->with('stores') // Load the stores relationship
            ->get()
            ->pluck('stores') // Extract stores collection
            ->flatten() // Flatten nested collections
            ->pluck('id') // Get store IDs
            ->unique() // Ensure unique IDs
            ->values(); // Reset keys

        return $storeIds;
    }


    public static function getUserSubscribedSections()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $subscription = self::getActiveSubscription($user->id);

        if (!$subscription) {
            return response()->json([
                'status' => false,
                'message' => 'No active subscription found',
                'data' => []
            ], 403);
        }

        // Get the sections with all their data
        return $subscription->package->sections()->with('stores')->get();
    }

    private static function getActiveSubscription($userId)
    {
        return Subscription::where('user_id', $userId)
            ->where('is_online', 1)
            ->latest()
            ->first();
    }
}
