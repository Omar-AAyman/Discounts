<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Mail\VerifyTokenMail;
use App\Models\UserSession;
use App\Models\Verifytoken;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;


class Authentication extends Controller
{

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'phone' => 'required|unique:users,phone',
            'country' => 'required',
            'city'    => 'required',
            'type' => 'required',
            'img' => 'nullable|mimes:jpg,png,jpeg,gif,svg',
        ]);

        $userSession = UserSession::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']), // securely hash the password
            'phone'      => $data['phone'],
            'country'    => $data['country'],
            'city'    => $data['city'],
            'type'       => $data['type'],
        ]);




        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName =  time() . '_' . $image->getClientOriginalName();

            $destinationPath = public_path('images/userImages');
            if (!file_exists($destinationPath . '/' . $imageName)) {

                $image->move(public_path('images/userImages'), $imageName);
            }

            $userSession->img = $imageName;
            $userSession->save();
        }

        $validToken = random_int(100000, 999999); // Generates a 6-digit random number
        $get_token = new Verifytoken();
        $get_token->token =  $validToken;
        $get_token->email =  $data['email'];
        $get_token->save();
        $user_email = $data['email'];
        $user_first_name = $data['first_name'];
        $user_last_name = $data['last_name'];

        Mail::to($data['email'])->send(new VerifyTokenMail($user_email, $validToken, $user_first_name, $user_last_name));

        return response([
            "status" => true,
            "message" => __('messages.otp_sent', [], 'ar'),
        ], 200);
    }


    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'fcm_token' => 'required|string',
        ]);

        // Find the user before authentication
        $user = User::where('email', $request->email)->first();

        // Case 1: User does not exist
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.user_not_found', [], 'ar'),
            ], 200);
        }

        // Case 2: User is deactivated
        if ($user->is_online == 0) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.account_deactivated', [], $user->lang ?? 'ar'),
            ], 200);
        }


        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = User::where('email', $request->email)->first();
            $lang = $user->lang ?? 'ar';

            // dd($user->stores[0]->status);
            $sellerStatus = null;
            if ($user->type == 'seller') {
                $sellerStatus = $user->stores[0] ? $user->stores[0]->status : null;

                if ($sellerStatus == 'pending') {

                    // Return success response with user and token
                    return response()->json([
                        'status' => 'error', // Added status
                        'message' => __('messages.store_pending', [], $lang),
                        'token' => null,
                        'user_type' =>  null,
                        'seller_type_id' =>  null,
                        'seller_status' =>  $sellerStatus,
                        'details' => []
                    ]);
                }
            }

            // Update the user's FCM token
            $user->fcm_token = $request->fcm_token;
            $user->save();

            // $user= User::with('stores')->find($request->user()->id);

            $lastSubscription = $user->subscriptions()->where('is_online', 1)->first();
            $subscriptionPackageName = $lastSubscription ? $lastSubscription->package->name : 'N/A';
            $userStore = $user->stores[0] ?? null;
            $sector_qr = $userStore ? $userStore->sector_qr : null;
            $user->sector_qr = $sector_qr;
            $lastSession = $user->lastSessionBeforeCurrent() ? $user->lastSessionBeforeCurrent()->last_activity : null;

            // Create Token with Expiration
            $token = $user->createToken('API Token');

            // Set Expiration Time (e.g., 30 days)
            $token->accessToken->expires_at = now()->addDays(30);
            $token->accessToken->save();
            if ($lastSubscription && (!$lastSubscription->is_online || now()->greaterThan($lastSubscription->expires_at))) {
                $lastSubscription->update(['is_online' => false]); // Mark as inactive if expired
            }

            // Return success response with user and token
            return response()->json([
                'status' => 'success', // Added status
                'message' => __('messages.login_success', [], $lang),
                'token' => $token->plainTextToken,
                'user_type' => $user->type,
                'seller_type_id' => $user->seller_type_id,
                'seller_status' => $sellerStatus,
                'last_session' => $lastSession,
                'details' => [
                    $user->type => $user,
                    'subscription_details' => $lastSubscription ?? null,
                ]
            ]);
        }

        // If authentication fails
        return response()->json([
            'status' => 'error',
            'message' => __('messages.invalid_credentials', [], 'ar'),
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $lang = $user->lang ?? 'ar';

        if (!PersonalAccessToken::findToken($request->bearerToken())->isExpired()) {
            $request->user()->tokens()->delete();


            return response()->json([
                'status' => true,
                'message' => __('messages.logout_success', [], $lang),
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => __('messages.token_expired', [], $lang),
            ], 200);
        }
    }

    // Forget password

    public function forgetPassword(Request $request)
    {

        $request->validate([
            'email' => 'required'

        ]);
        $user = User::where('email', $request->email)->first();
        $lang = $user->lang ?? 'ar';


        if ($user) {
            $validToken = random_int(100000, 999999); // Generates a 6-digit random number
            $get_token = new Verifytoken();
            $get_token->token =  $validToken;
            $get_token->email =  $user->email;
            $get_token->save();
            $get_user_email = $user->email;
            $get_user_first_name = $user->first_name;
            $get_user_last_name = $user->last_name;

            Mail::to($user->email)->send(new VerifyTokenMail($get_user_email, $validToken, $get_user_first_name, $get_user_last_name));

            return response()->json([
                "status" => true,
                "message" => __('messages.otp_sent', [], $lang),
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => __('messages.user_not_found', [], $lang),
            ], 200);
        }
    }
}
