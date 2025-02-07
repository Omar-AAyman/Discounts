<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Verifytoken;
use Illuminate\Http\Request;

class VerifyTokenChangePasswordApi extends Controller
{

    public function verifyToken(Request $request) {
    
    $request->validate([
        'email' => 'required',
        'otp_code' => 'required',
    ]);

    $otpCode = $request->otp_code;

    // Verify the OTP code
    $verifyToken = Verifytoken::where('token', $otpCode)->where('email',$request->email)->first();

    if ($verifyToken) {
        // Mark the token as activated
        $verifyToken->is_activated = 1;
        $verifyToken->save();



        // Delete the OTP record after successful activation
          $verifyToken->delete();
    

    
        return response([
                     'status' => 'success',
                     'message' => 'OTP verified successfully',
                 ]);
 
    }


    else {
        return response([
            'status' => 'error',
            'message' => 'Invalid OTP code. Please try again.',
        ]);
    }


}
}
