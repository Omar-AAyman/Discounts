<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Models\PushNotificationUserLog;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    // Temporarily placed here. This function should be in the admin panel.
    /**
     * Send a push notification to selected users.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotification(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'en_title' => 'required|string',
            'ar_title' => 'required|string',
            'en_description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'type' => 'required|in:general,promotional,transactional',
            'user_ids' => 'required|array', // List of users to send notification to
            'user_ids.*' => 'exists:users,id', // Ensure that each user id exists in the users table
        ]);

        // Create a new notification entry in the PushNotification table
        $notification = PushNotification::create([
            'en_title' => $request->en_title,
            'ar_title' => $request->ar_title,
            'en_description' => $request->en_description,
            'ar_description' => $request->ar_description,
            'type' => $request->type,
            'is_active' => true, // Ensure the notification is active
        ]);

        // Attach users to the notification by creating logs in PushNotificationUserLog
        foreach ($request->user_ids as $userId) {
            PushNotificationUserLog::create([
                'push_notification_id' => $notification->id,
                'user_id' => $userId,
                'is_viewed' => false, // Initially mark the notification as not viewed
            ]);
        }

        // Return a response confirming the notification was sent successfully
        return response()->json([
            'status' => true,
            'message' => 'Notification sent successfully.',
        ]);
    }

    /**
     * Get a list of notifications for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNotifications(Request $request)
    {
        $userId = auth()->id(); // Get the authenticated user's ID

        // Retrieve notifications for the user along with the associated log data
        $notifications = PushNotificationUserLog::where('user_id', $userId)
            ->with('pushNotification') // Eager load the associated push notification
            ->orderBy('created_at', 'desc') // Order notifications by creation date (newest first)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->pushNotification->id,
                    'title' => [
                        'en' => $log->pushNotification->en_title,
                        'ar' => $log->pushNotification->ar_title,
                    ],
                    'description' => [
                        'en' => $log->pushNotification->en_description,
                        'ar' => $log->pushNotification->ar_description,
                    ],
                    'type' => $log->pushNotification->type,
                    'is_viewed' => $log->is_viewed,
                    'viewed_at' => $log->viewed_at,
                    'created_at' => $log->pushNotification->created_at,
                ];
            });

        // Return the list of notifications
        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a specific notification as viewed by the authenticated user.
     *
     * @param int $notificationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsViewed($notificationId)
    {
        $userId = auth()->id(); // Get the authenticated user's ID

        // Find the corresponding log entry for the user and notification
        $log = PushNotificationUserLog::where('push_notification_id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        // If no log entry exists for the user and notification, return an error
        if (!$log) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found for this user.',
            ], 404);
        }

        // If the notification is already viewed, return a message indicating so
        if ($log->is_viewed) {
            return response()->json([
                'status' => true,
                'message' => 'Notification already viewed.',
            ]);
        }

        // Mark the notification as viewed and set the 'viewed_at' timestamp
        $log->update([
            'is_viewed' => true,
            'viewed_at' => now(),
        ]);

        // Return a success message indicating the notification was marked as viewed
        return response()->json([
            'status' => true,
            'message' => 'Notification marked as viewed.',
        ]);
    }
}
