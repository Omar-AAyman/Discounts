<?php

namespace App\Services\notifications;

use Firebase\JWT\JWT;
use Google\Client;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FirebaseService
{
    protected $client;
    protected $url;

    public function __construct() {
        // Load FCM URL and Service Account Credentials from config
        // $this->credentials = json_decode(file_get_contents(storage_path(config('services.fcm.credentials'))), true);

        $this->url = config('services.fcm.url');
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path(config('services.fcm.credentials')));
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    }

     /**
     * Send a notification to a specific device using Firebase Cloud Messaging (FCM).
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $body
     * @return array
     */
    public function sendNotification($deviceTokens, $title, $body)
    {
        // $accessToken = $this->getAccessToken();
        $accessToken = $this->client->fetchAccessTokenWithAssertion()['access_token'];
        if (!$accessToken) {
            return response()->json(['Error' => 'Failed to retrieve access token.'], 500);
        }


        // Send notification via FCM API v1
        $response = Http::withToken($accessToken)->post($this->url, [
            'message' => [
                'tokens' => $deviceTokens,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ],
        ]);

        if ($response->failed()) {
            return response()->json([
                'Error' => [
                    'status' => false,
                    'code' => $response->status(),
                    'message' => $response->body(),
                ],
            ], 400);
        }

        return $response->json();
    }
}
