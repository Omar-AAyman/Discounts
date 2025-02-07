<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class ApplicationStatusApi extends Controller
{
    public function appStatus(){
        $option = Option::where('key','application_status')->first();
        if($option){
        $status = $option->value ;
        return response(['application statsu'=>$status]);
        }
        return response(['message'=>'no status defined']);
    }
}
