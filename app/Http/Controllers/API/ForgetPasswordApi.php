<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgetPasswordApi extends Controller
{
    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::where('email', $request->email)->first();

            // Get user's preferred language (default: 'ar')
            $lang = $user->lang ?? 'ar';

            if (!$user) {
                return response()->json([
                    'status' => 'false',
                    'message' => __('messages.user_not_found', [], $lang),
                ], 404);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'status' => 'true',
                'message' => __('messages.password_updated', [], $lang),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'false',
                'message' => __('messages.validation_failed', ['errors' => implode(', ', $e->errors())], 'ar'),
            ], 422);
        }
    }
}
