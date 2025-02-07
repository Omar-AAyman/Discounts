<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OnBoarding;
use Illuminate\Http\Request;

class OnBoardingScreenApi extends Controller
{
    public function onBoardings(){
        $onBoardings = OnBoarding::all();

        return response([
            'status'=>'success',
            'data'=> $onBoardings,
        ]);
    }
}
