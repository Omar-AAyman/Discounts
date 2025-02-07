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

    public function register(Request $request){

        $data = $request->validate([
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed',
            'phone'=>'required|unique:users,phone',
            'country'=>'required',
            'type'=>'required',
            'img' => 'nullable|mimes:jpg,png,jpeg,gif,svg',
        ]);

        $userSession = UserSession::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']), // securely hash the password
            'phone'      => $data['phone'],
            'country'    => $data['country'],
            'type'       => $data['type'],
        ]);




        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = $image->getClientOriginalName();

            $destinationPath = public_path('userImages');
            if (!file_exists($destinationPath . '/' . $imageName)) {

            $image->move(public_path('userImages'), $imageName);
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

           Mail::to($data['email'])->send(new VerifyTokenMail($user_email,$validToken,$user_first_name,$user_last_name));

           return response([

            "status" => "تم ارسال رمز otp ","إلى حساب الايميل",

           ]);



    }


    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'fcm_token' => 'required|string',
        ]);

    // Attempt to authenticate the user
    if (Auth::attempt($request->only('email', 'password'))) {
        $user = User::where('email',$request->email)->first();

        // Update the user's FCM token
        $user->fcm_token = $request->fcm_token;
        $user->save();

        // Return success response with user and token
        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken,
            'user_type'=>$user->type,
            'details'=> [
                $user->type => $user,
            ]
        ]);
    }

    // If authentication fails
    return response()->json([
        'status' => 'error',
        'message' => 'Invalid email or password',
    ], 401);
    }

    public function logout(Request $request){

    if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){


       $request->user()->tokens()->delete();


        return [
            'message' => 'Logged out'
        ];

    }
    else{
        return response(['message'=>'token is expired']);

    }
    }

    // change password

    public function changePassword(Request $request){

    if(!PersonalAccessToken::findToken($request->bearerToken())->isExpired()){

         $request->validate( [
           'email'=>'required'

        ]);
        $user = User::where('email',$request->email)->first();


        if($user){
            $validToken = random_int(100000, 999999); // Generates a 6-digit random number
            $get_token = new Verifytoken();
            $get_token->token =  $validToken;
            $get_token->email =  $user->email;
            $get_token->save();
            $get_user_email = $user->email;
            $get_user_first_name = $user->first_name;
            $get_user_last_name = $user->last_name;

            Mail::to($user->email)->send(new VerifyTokenMail($get_user_email,$validToken,$get_user_first_name,$get_user_last_name));

            return response([

            "status" => "تم ارسال رمز otp ","إلى حساب الايميل",
            ]);

    }
    else{
        return response(['message'=>'user dosn\'t exist']);
    }




    }

    else{
        return response(['message'=>'token is expired']);

    }

    }



}
