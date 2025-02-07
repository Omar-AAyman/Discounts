<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class ContactApi extends Controller
{

    public function userMessage(Request $request){


        $request->validate([
            'email'=>'required',
            'message'=>'required',
            
        ]);
            $user = User::where('email',$request->email)->first();

            if($user){

                Ticket::create([

                    'title'=>'contact message',
                    'user_id'=> $user->id ,
                    'body'=>  $request->message,
                ]);

                return response(['message'=>'message was sent to wallet deals admins']);

            }

            else{

                return response(['message'=>'user doesn\'t exist']);
            }


    

    }
    
}
