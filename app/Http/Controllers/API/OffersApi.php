<?php

namespace App\Http\Controllers\API;

use App\Helpers\SubscriptionHelper;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferNotification;
use App\Models\Store;
use App\Models\Subscription;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class OffersApi extends Controller
{

    public function offers()
    {
        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds; // Return response if unauthorized or no subscription
        }

        // Fetch only offers from subscribed stores
        $offers = Offer::whereIn('store_id', $storeIds)
            ->where('is_online', 1)
            ->whereHas('store', function ($query) {
                $query->where('is_online', 1)
                      ->where('status', '!=', 'pending'); // استبعاد المتاجر التي حالتها "pending"
            })
            ->with('store.user')
            ->get()
            ->map(function ($offer) {
                // Calculate discount percentage if missing
                if ($offer->discount_percentage !== null) {
                    $offer->computed_discount = $offer->discount_percentage;
                } elseif ($offer->discount_amount !== null && $offer->price_before_discount > 0) {
                    $offer->computed_discount = ($offer->discount_amount / $offer->price_before_discount) * 100;
                } else {
                    $offer->computed_discount = 0; // No discount available
                }
                return $offer;
            })
            ->sortByDesc('computed_discount') // Sort by computed discount percentage (highest first)
            ->values(); // Reset array keys

        return response(['offers' => $offers]);
    }

    public function offer(Request $request)
    {
        $request->validate(['id' => 'required']);

        $storeIds = SubscriptionHelper::getUserSubscribedStoreIds();

        if ($storeIds instanceof \Illuminate\Http\JsonResponse) {
            return $storeIds; // Return response if unauthorized or no subscription
        }
        // Fetch the offer only if it belongs to an allowed store
        $offer = Offer::where('id', $request->id)
            ->whereIn('store_id', $storeIds)
            ->with('store')
            ->first();
        if ($offer) {
            return response(['offer' => $offer]);
        } else {
            return response(['message' => 'offer doesn\'t exist']);
        }
    }

    public function offerProducts(Request $request)
    {
        $request->validate(['id' => 'required']);
        $id = $request->id;

        $offer = Offer::find($id);
        if ($offer) {
            $offerProdcuts = $offer->products;
            if (count($offerProdcuts) > 0) {

                return response(['offer products' => $offerProdcuts]);
            } else {
                return response(['message' => 'no products belong to this offer']);
            }
        } else {
            return response(['message' => 'offer doesn\'t exist']);
        }
    }

    public function updateOfferDiscount(Request $request)
    {

        $request->validate([
            'offer_id' => 'required',
            'new_discount' => 'required|numeric|min:1|max:100',
        ]);

        $offer = Offer::find($request->offer_id);
        $user = $request->user();

        if ($offer) {

            if ($offer->store->user->id === $user->id) {


                OfferNotification::create([

                    'offer_id' => $offer->id,
                    'status' => 'pending',
                    'new_discount_percentage' => $request->new_discount,
                ]);

                return response(['message' => 'update request was sent to admins']);
            } else {
                return response(['message' => 'unauthorized access']);
            }
        } else {
            return response(['message' => 'offer doesn\'t exist']);
        }
    }
}
