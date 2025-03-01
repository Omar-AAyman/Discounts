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

            if (!$user) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'User not found',
                ], 404);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            return response()->json([
                'status' => 'true',
                'message' => 'Password successfully updated',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'status' => 'false',
                'message' => 'Validation failed: ' . implode(', ', $e->errors()),
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'status' => 'false',
                'message' => 'Process failed: ' . $e->getMessage(),
            ]);
        }
    }
}
