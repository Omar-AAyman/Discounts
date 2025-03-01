<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomerRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class CustomerRequestApi extends Controller
{
    // for logged customer
    public function requestProduct(Request $request)
    {
        if (!PersonalAccessToken::findToken($request->bearerToken())) {
            return response(['status' => false, 'message' => 'token is invalid'], 401);
        }

        if (PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            return response(['status' => false, 'message' => 'token is expired'], 401);
        }

        $product_id = $request->product_id;

        if (is_null($product_id)) {
            return response(['status' => false, 'message' => 'product_id is required'], 200);
        }

        $user = $request->user();
        $product = Product::find($product_id);

        if (!$product) {
            return response(['status' => false, 'message' => 'product not found'], 200);
        }

        // Check if the user already has a pending request for the same product
        $existingRequest = CustomerRequest::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return response(['status' => false, 'message' => 'you already have a pending request for this product'], 200);
        }

        CustomerRequest::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => 'pending',
        ]);

        return response(['status' => true, 'message' => 'request to purchase the product was sent']);
    }


    // for logged seller
    public function unconfirmedPurchaseRequests(Request $request)
    {
        if (!PersonalAccessToken::findToken($request->bearerToken())) {
            return response(['status' => false, 'message' => 'token is invalid'], 401);
        }

        if (PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            return response(['status' => false, 'message' => 'token is expired'], 401);
        }

        $seller = $request->user();

        // Retrieve customer requests for products that belong to the logged-in seller
        $customerRequests = CustomerRequest::where('status', 'pending')
            ->whereHas('product.store', function ($query) use ($seller) {
                $query->where('user_id', $seller->id);
            })
            ->get();

        if ($customerRequests->isEmpty()) {
            return response(['status' => false, 'message' => 'no unconfirmed purchase requests found'], 200);
        }

        return response(['status' => true, 'message' => 'unconfirmed purchase requests retrieved', 'data' => $customerRequests], 200);
    }
}
