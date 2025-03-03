<?php

namespace App\Http\Controllers\API\Sellers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class OfferController extends Controller
{
    /**
     * Add a new offer and associated products.
     *
     * @param StoreOfferRequest $request
     * @return JsonResponse
     */
    public function addOffer(StoreOfferRequest $request): JsonResponse
    {
        $seller = auth()->user();
        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 200); // Not Found
        }

        // Step 3: Fetch the store related to the seller
        $store = Store::where('user_id', $seller->id)->first();

        // Create the offer
        $offer = Offer::create([
            'title' => $request->name,
            'discount_percentage' => $request->discount_percentage ? $request->discount_percentage : null,
            'discount_amount' => $request->discount_amount ? $request->discount_amount : null,
            'price_before_discount' => $request->price_before_discount,
            'bg_img' => $request->bg_img,
            'store_id' => $store ? $store->id : null,
            'is_online' => 1,
        ]);

        // Load the associated products after creating the offer
        $offer->load('products'); // Load the products relationship

        // Create associated product
        Product::create(attributes: [
            'store_id' => $store ? $store->id : null,
            'offer_id' => $offer->id,
            'name' => $request->name,
            'price' => $request->price_before_discount,
            'discount_percentage' => $request->discount_percentage ? $request->discount_percentage : null,
            'discount_amount' => $request->discount_amount ? $request->discount_amount : null,
            'is_excluded_from_discount' => 0,
        ]);

        // Handle image uploads
        $this->handleOfferImageUploads($request, $offer);

        return response()->json([
            'status' => true,
            'message' => 'Offer added successfully!',
            'data' => new OfferResource($offer),
        ]);
    }

    /**
     * Get all offers for the authenticated seller with their products.
     *
     * @return JsonResponse
     */
    public function getUserOffers(): JsonResponse
    {
        $seller = auth()->user();

        // Step 2: Validate if seller exists
        if (!$seller) {
            return response()->json([
                'status' => false,
                'message' => 'We could not find your account.',
            ], 200); // Not Found
        }

        // Step 3: Fetch the store related to the seller
        $store = Store::where('user_id', $seller->id)->first();

        // Check if seller has an associated store
        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'No store found for this seller.',
            ], 404); // Not Found
        }

        $offers = Offer::where('store_id', $store->id)->with('products')->get(); // Assuming a relationship exists

        // Check if any offers exist
        if ($offers->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No offers found for this store.',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => OfferResource::collection($offers),
        ]);
    }

    /**
     * Update an existing offer.
     *
     * @param UpdateOfferRequest $request
     * @param int $offerId
     * @return JsonResponse
     */
    public function updateOffer(UpdateOfferRequest $request, $offerId): JsonResponse
    {
        $offer = Offer::findOrFail($offerId);

        $offer->update([
            'discount_percentage' => $request->discount_percentage,
            'discount_amount' => $request->discount_amount ? $request->discount_amount : null,
            'price_before_discount' => $request->price_before_discount,
            'img' => $request->bg_img,
        ]);

        // Update associated product
        $product = Product::where('offer_id', $offer->id)->first(); // Fetch the existing product
        if ($product) {
            $product->update([ // Update the existing product
                'name' => $request->name,
                'discount_percentage' => $request->discount_percentage ? $request->discount_percentage : null,
                'discount_amount' => $request->discount_amount ? $request->discount_amount : null,
                'price' => $request->price_before_discount,
                'is_excluded_from_discount' => 0,
            ]);
        } else {
            // If no product exists, create a new one (optional)
            Product::create([
                'store_id' => $offer->store_id, // Ensure you have the store ID available
                'offer_id' => $offer->id,
                'name' => $request->name,
                'price' => $request->price_before_discount,
                'discount_percentage' => $request->discount_percentage ? $request->discount_percentage : null,
                'discount_amount' => $request->discount_amount ? $request->discount_amount : null,
                'is_excluded_from_discount' => 0,
            ]);
        }

        // Handle image uploads
        $this->handleOfferImageUploads($request, $offer);

        // Reload the offer to get the updated data
        $offer->load('products'); // Reload the offer with updated data

        return response()->json([
            'status' => true,
            'message' => 'Offer updated successfully!',
            'data' => new OfferResource($offer), // Return updated offer data
        ]);
    }

    /**
     * Mark an offer as offline.
     *
     * @param int $offerId
     * @return JsonResponse
     */
    public function markOfferOffline($offerId): JsonResponse
    {
        $offer = Offer::findOrFail($offerId);
        $offer->load('products');

        // Check if the offer is already offline
        if ($offer->is_online === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Offer is already marked as offline.',
                'data' => new OfferResource($offer),
            ], 200); // Not Found
        }

        $offer->update(['is_online' => 0]);

        return response()->json([
            'status' => true,
            'message' => 'Offer marked as offline successfully!',
            'data' => new OfferResource($offer),
        ]);
    }

    /**
     * Handle the image uploads for offers and associated products.
     * This method moves the uploaded images to the designated directories.
     *
     * @param Request $request
     * @param Offer $offer
     * @return void
     */
    private function handleOfferImageUploads(Request $request, Offer $offer)
    {
        // Handle bg_img upload
        $image = $request->file('bg_img');
        $imageName = hash('sha256', time() . Str::random(10)) . '.' . $image->getClientOriginalExtension(); // Hashed filename
        $destinationPath = public_path('images/offerImages');

        // Move the new image
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $image->move($destinationPath, $imageName);
        $offer->bg_img = $imageName; // Update offer image

        // Save updated offer data
        $offer->save();
    }
}
