<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;

class CountriesApi extends Controller
{
    public function cities(){
        $countries = Country::select('id','name','name_ar')->get();
        return response([
            'status'=> true ,
            'data'=> $countries
        ],200);
    }
}
