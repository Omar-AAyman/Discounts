<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class StoresApi extends Controller
{
    public function stores(){

        $stores = Store::where('is_online',1)->get();
        return response(['stores'=>$stores]);
    }

    public function storesWithOffers(){

        $storesWithOffers = Store::has('offers')->get();

        return response(['stores with offers'=>$storesWithOffers]);
    }

    public function store(Request $request){
        $request->validate(['uuid'=>'required']);
        $uuid = $request->uuid;
        $store = Store::where('uuid',$uuid)->first();
        if($store){

            return response(['store'=>$store]);

        }
        else{
            return response(['message'=>'store doesn\'t exist']);
        }
    }

    public function mostPopularStores(){
        $stores = Store::where('is_most_popular',1)->get();
        if(count($stores)>0){
            return response(['most popular providers'=>$stores]);
        }
        else{
            return response(['message'=>'there\'s no most popular providers defined']);

        }
    }

    public function filterStores(Request $request){
        $data = $request->validate([
            'city'=>'required|string',
            'country'=>'required|string',
        ]);

        $stores = Store::where('city',$data['city'])->where('country',$data['country'])->get();
        if(count($stores)>0){
            return response(['stores'=>$stores]);
        }
        else{
            return response(['message'=>'no matched stores']);
        }
    }
}
