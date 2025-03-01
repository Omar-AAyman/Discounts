<?php

namespace App\Helpers;

use App\Models\PushNotification;
use App\Models\PushNotificationUserLog;

class PushNotifications
{
    /**
     * Logs a push notification sent to a user and returns the log entry ID.
     *
     * This helper function records the user ID, push notification details in Arabic and English, and the associated click action.
     * It creates a log entry with this information and returns the ID of the created log entry.
     *
     * @param int $userId The ID of the user receiving the push notification.
     * @param object $notificationDetails An object containing the push notification details, including Arabic and English titles and descriptions.
     * @return int The ID of the log entry created.
     */
    public static function log($userId, $notificationDetails)
    {
        // ✅ Step 1: Create a new push notification record
        $pushNotification = PushNotification::create([
            'en_title' => $notificationDetails->en_title,
            'ar_title' => $notificationDetails->ar_title,
            'en_description' => $notificationDetails->en_description,
            'ar_description' => $notificationDetails->ar_description,
            'type' => $notificationDetails->type ?? 'general',
        ]);

        $pushNotificationId = isset($pushNotification->id) ? $pushNotification->id : null; // Initialize the variable to store the push notification ID, which is currently not used.

        // Create a log entry for the push notification
        $log = PushNotificationUserLog::create([
            'push_notification_id' => $pushNotificationId, // The ID of the push notification, currently not used.
            'user_id' => $userId, // The ID of the user receiving the notification.
        ]);

        return $log->id; // Return the ID of the log entry created.
    }
}
