<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountriesApi extends Controller
{



    public function countries(){
        $countries = Country::all();
        return response([
            'countries'=>$countries->pluck('name'),
        ]);
    }
}
