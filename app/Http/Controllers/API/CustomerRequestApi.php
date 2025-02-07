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
    public function requestProduct(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){
       $product_id = $request->product_id;
       $user = $request->user();
       $product = Product::findOrFail($product_id);
       CustomerRequest::create([
        'user_id'=>$user->id,
        'product_id'=>$product->id,
        'status'=>'pending',
       ]);

       return response(['message'=>'request to purchase the product was sent']);
       
       
        }
        else{
            return response(['message'=>'token is expired']);
        }

    }


    // for logged seller 
    public function unconfirmedPurchaseRequests(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){

            $seller = $request->user();

            // Retrieve customer requests for products that belong to the logged-in seller
            $customerRequests = CustomerRequest::where('status', 'pending')
                ->whereHas('product.store', function ($query) use ($seller) {
                    $query->where('user_id', $seller->id);
                })
               
                ->get();   

            return response(['unconfirmed purchase requests'=>$customerRequests]);
        }

        else{
            return response(['message'=>'token is expired']);
        }
    }
}
