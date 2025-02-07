<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

use function PHPUnit\Framework\isEmpty;

class FavoritesApi extends Controller
{
    public function userFavoriteOffers(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){
         $user = $request->user();
         $favOffers = $user->favorites()->where('store_id',null)->with('offer')->get()->pluck('offer');
         if(count($favOffers)>0){
              return response(['favorite offers'=>$favOffers]);
         }
         else{
              return response(['message'=>'there are no favorite offers']);
         }
       
        }

        else{
            return response(['message'=>'token is expired']);
        }

    }



    public function userFavoriteStores(Request $request){
        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){
         $user = $request->user();
         $favStores = $user->favorites()->with('store')->where('offer_id',null)->get()->pluck('store');
         if(count($favStores)>0){
              return response(['favorite stores'=>$favStores]);
         }
         else{
              return response(['message'=>'there are no favorite stores']);
         }
       
        }

        else{
            return response(['message'=>'token is expired']);
        }

    }
}
