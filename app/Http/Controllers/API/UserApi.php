<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class UserApi extends Controller
{
    public function profile(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){

        $lastSubscription = $request->user()->subscriptions()->where('is_online',1)->first();
        $subscriptionPackageName= $lastSubscription->package ? $lastSubscription->package->name : 'N/A';

        return response(['user'=>$request->user()
        ,'subscription package'=>$subscriptionPackageName]);

        }
        else{
            return response(['message'=>'tolen is expired']);
        }
    }
}
