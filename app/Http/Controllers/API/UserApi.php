<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class UserApi extends Controller
{
    public function profile(Request $request)
    {
        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {

            $user = User::with('stores')->find($request->user()->id);
            // dd($user->stores[0]->status);
            $sellerStatus = null;
            if ($user->type == 'seller') {
                $sellerStatus = $user->stores[0] ? $user->stores[0]->status : null;
            }

            $lastSubscription = $request->user()->subscriptions()->where('is_online', 1)->first();
            $subscriptionPackageName = $lastSubscription ? $lastSubscription->package->name : 'N/A';
            $userStore = $user->stores[0] ?? null;
            $sector_qr = $userStore ? $userStore->sector_qr : null;
            $user->sector_qr = $sector_qr;
            $lastSession = $user->lastSessionBeforeCurrent() ? $user->lastSessionBeforeCurrent()->last_activity : null;

            if ($lastSubscription && (!$lastSubscription->is_online || now()->greaterThan($lastSubscription->expires_at))) {
                $lastSubscription->update(['is_online' => false]); // Mark as inactive if expired
            }

            // Return success response with user and token
            return response()->json([
                'status' => 'success', // Added status
                'message' => 'User data retrieved successfully.',
                'user_type' => $user->type,
                'seller_type_id' => $user->seller_type_id,
                'seller_status' => $sellerStatus,
                'last_session' => $lastSession,
                'details' => [
                    $user->type => $user,
                    'subscription_details' => $lastSubscription ?? null,
                ]
            ]);
        } else {
            return response(['message' => 'token is expired']);
        }
    }

    /**
     * Get the current user's language
     */
    public function getUserLang(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'message' => 'User language retrieved successfully.',
            'lang' => $user->lang,
        ]);
    }

    /**
     * Update the user's language
     */
    public function updateUserLang(Request $request)
    {
        $request->validate([
            'lang' => 'required|string|in:en,ar', // Modify allowed languages if needed
        ]);

        $user = $request->user();
        $user->lang = $request->lang;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User language updated successfully.',
            'lang' => $user->lang,
        ]);
    }
}
