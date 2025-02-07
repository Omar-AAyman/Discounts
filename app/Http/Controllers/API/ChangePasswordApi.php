<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class ChangePasswordApi extends Controller
{
    public function changePassword(Request $request){


        if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){
       
            try{
            $request->validate([
                'new_password'=>'required|string|confirmed',
            ]);

            $user = $request->user();
            $user->update([
                'password'=>Hash::make($request->new_password)
            ]);
            return response()->json([
                'status'=>'success',
                'message'=>'Password successfully updated',
            ]);
       
        

        }catch (\Exception $e) {
            // Handle failure in the inner process
            return response()->json([
                'status' => 'error',
                'message' => 'Process failed: ' . $e->getMessage(),
           ]);
        }


    }
        else{
            return response(['message'=>'token is expired']);
    
        }
    }
}
