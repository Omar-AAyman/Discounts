<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


/**
 * Class FavoritesApi
 * Handles user favorite offers and stores operations.
 */
class FavoritesApi extends Controller
{
    /**
     * Retrieves the user's favorite offers.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function userFavoriteOffers(Request $request)
    {
        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            $user = $request->user();
            $favOffers = $user->favorites()->where('store_id', null)->with(['offer.store'])->get()->pluck('offer');

            if (count($favOffers) > 0) {
                return response(['status' => true, 'favorite offers' => $favOffers], 200);
            } else {
                return response(['status' => false, 'message' => 'there are no favorite offers'], 200);
            }
        } else {
            return response(['status' => false, 'message' => 'token is expired'], 200);
        }
    }

    /**
     * Toggles an offer in the user's favorites.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function toggleFavoriteOffer(Request $request)
    {
        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            $request->validate([
                'offer_id' => 'required|exists:offers,id',
            ]);

            $user = $request->user();
            $offerId = $request->input('offer_id');

            // Check if the offer is already in favorites
            $existingFavorite = $user->favorites()->where('offer_id', $offerId)->first();

            if ($existingFavorite) {
                $existingFavorite->delete(); // Remove from favorites
                return response(['status' => true, 'message' => 'Offer removed from favorites'], 200);
            } else {
                $user->favorites()->create(['offer_id' => $offerId]); // Add to favorites
                return response(['status' => true, 'message' => 'Offer added to favorites'], 200);
            }
        } else {
            return response(['message' => 'token is expired']);
        }
    }

    /**
     * Retrieves the user's favorite stores.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function userFavoriteStores(Request $request)
    {
        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            $user = $request->user();
            $favStores = $user->favorites()->with('store')->where('offer_id', null)->get()->pluck('store');
            if (count($favStores) > 0) {
                return response(['status' => true, 'favorite stores' => $favStores], 200);
            } else {
                return response(['status' => false, 'message' => 'there are no favorite stores'], 200);
            }
        } else {
            return response(['message' => 'token is expired']);
        }
    }

    /**
     * Toggles a store in the user's favorites.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function toggleFavoriteStore(Request $request)
    {
        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            $request->validate([
                'store_id' => 'required|exists:stores,id',
            ]);

            $user = $request->user();
            $storeId = $request->input('store_id');

            // Check if the store is already in favorites
            $existingFavorite = $user->favorites()->where('store_id', $storeId)->first();

            if ($existingFavorite) {
                $existingFavorite->delete(); // Remove from favorites
                return response(['status' => true, 'message' => 'Store removed from favorites'], 200);
            } else {
                $user->favorites()->create(['store_id' => $storeId]); // Add to favorites
                return response(['status' => true, 'message' => 'Store added to favorites'], 200);
            }
        } else {
            return response(['message' => 'token is expired']);
        }
    }
}
