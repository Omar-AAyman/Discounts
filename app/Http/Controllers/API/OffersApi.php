<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferNotification;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class OffersApi extends Controller
{
    
    public function offers(){

        $offers = Offer::where('is_online',1)->with('store')->get();
        return response(['offers'=>$offers]);
    }

    public function offer(Request $request){
        $request->validate(['id'=>'required']);
        $id = $request->id;
        $offer = Offer::where('id',$id)->with('store')->first();
        if($offer){
            return response(['offer'=>$offer]);
        }
        else{
            return response(['message'=>'offer doesn\'t exist']);
        }
    }

    public function offerProducts(Request $request){
        $request->validate(['id'=>'required']);
        $id = $request->id;

        $offer = Offer::find($id);
        if($offer){
            $offerProdcuts = $offer->products ;
            if(count($offerProdcuts)>0){

                return response(['offer products'=>$offerProdcuts]);
            }
            else{
                return response(['message'=>'no products belong to this offer']);

            }
        }
        else{
            return response(['message'=>'offer doesn\'t exist']);
        }
    }

    public function updateOfferDiscount(Request $request){

        $request->validate([
            'offer_id'=>'required',
            'new_discount'=>'required|numeric|min:1|max:100',
          ]);

        $offer = Offer::find($request->offer_id);
        $user = $request->user();

        if($offer){

            if($offer->store->user->id === $user->id){
  

              OfferNotification::create([

                'offer_id'=>$offer->id,
                'status'=>'pending',
                'new_discount_percentage'=>$request->new_discount,
              ]);
              
              return response(['message'=>'update request was sent to admins']);

        } 

        else{
                return response(['message'=>'unauthorized access']);
        }

    }

        else{
            return response(['message'=>'offer doesn\'t exist']);
        }


    }
}
