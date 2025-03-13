<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use App\Models\Verifytoken;
use Illuminate\Http\Request;

class VerifyTokenRegisterApi extends Controller
{



    public function verifyToken(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'otp_code' => 'required',
        ]);

        $otpCode = $request->otp_code;

        // Verify the OTP code
        $verifyToken = Verifytoken::where('token', $otpCode)->first();

        if ($verifyToken) {
            // Mark the token as activated
            $verifyToken->is_activated = 1;
            $verifyToken->save();

            // Retrieve session data
            $sessionUser = UserSession::where('email',$request->input('email'))->first();

            // Check if the session email matches the request email
            if ($sessionUser) {
                // Create a new user instance
                $user = new User();
                $user->first_name = $sessionUser['first_name'];
                $user->last_name = $sessionUser['last_name'];
                $user->email = $sessionUser['email'];
                $user->password = $sessionUser['password'];
                $user->phone = $sessionUser['phone'];
                $user->country = $sessionUser['country'];
                $user->city = $sessionUser['city'];
                $user->type = $sessionUser['type'];


                $user->img = $sessionUser['img']; // Save the image name or path


                $user->save();

                // Delete the OTP record after successful activation
                $verifyToken->delete();

                $lang = $user->lang ?? 'ar';


                return response([
                    'status' => 'success',
                    'message' => __('messages.otp_verified', [], $lang),
                ]);
            } else {
                return response([
                    'status' => 'error',
                    'message' => __('messages.email_mismatch', [], 'ar'), // Default to Arabic
                ]);
            }
        } else {
            return response([
                'status' => 'error',
                'message' => __('messages.invalid_otp', [], 'ar'), // Default to Arabic
            ]);
        }
    }

}
