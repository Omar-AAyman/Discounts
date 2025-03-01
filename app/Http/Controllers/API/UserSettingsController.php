<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserSettingsController extends Controller
{
    /**
     * Update the user's password.
     *
     * This method ensures that the old password is correct, and then updates the password with the new one if valid.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        // Validate the request to ensure the old password and new password are provided
        $request->validate([
            'old_password' => 'required', // The old password is required
            'new_password' => 'required|min:8|confirmed', // New password must be confirmed and at least 8 characters
        ]);

        $user = auth()->user(); // Get the authenticated user

        // Check if the old password provided matches the user's current password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'The old password is incorrect.',
            ], 200);
        }

        // Update the user's password with the new one and save
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Return success message
        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    /**
     * Toggle the user's push notification preference (enabled or disabled).
     *
     * This method updates the user's push notification preference and returns the previous and current states.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function togglePushNotifications(Request $request)
    {
        // Validate that the push notifications preference is either true or false
        $request->validate([
            'push_notifications_enabled' => 'required|boolean', // True or false for enabling/disabling notifications
        ]);

        $user = auth()->user(); // Get the authenticated user
        $previousState = $user->push_notifications_enabled; // Get the previous state of the push notifications

        // Update the user's push notification preference
        $user->push_notifications_enabled = $request->push_notifications_enabled;
        $user->save();

        // Return success message with previous and current states
        return response()->json([
            'status' => true,
            'message' => 'Push notification preference updated successfully.',
            'previous_state' => $previousState ? 'enabled' : 'disabled', // Show previous state (enabled/disabled)
            'current_state' => $user->push_notifications_enabled ? 'enabled' : 'disabled', // Show current state (enabled/disabled)
        ]);
    }
}
