<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CitiesApi extends Controller
{
    public function areas(){
        $cities = City::select('id','name','name_ar','country_id as city_id')->get();
        return response([
            'status'=> true ,
            'data'=> $cities
        ],200);
    }
}
