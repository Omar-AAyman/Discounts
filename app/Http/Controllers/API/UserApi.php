<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class UserApi extends Controller
{
    public function profile(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){

        $user= User::with('stores')->find($request->user()->id);

        $lastSubscription = $request->user()->subscriptions()->where('is_online',1)->first();
        $subscriptionPackageName= $lastSubscription ? $lastSubscription->package->name : 'N/A';
        $sector_qr = $user->stores[0]->sector_qr ? $user->stores[0]->getSectoreQrAttribute($user->stores[0]->sector_qr) : null;
        $request->user()->sector_qr = $sector_qr;

        return response([
            'user'=>$request->user(),
            'subscription package'=>$subscriptionPackageName,
        ]);

        }
        else{
            return response(['message'=>'tolen is expired']);
        }
    }
}
