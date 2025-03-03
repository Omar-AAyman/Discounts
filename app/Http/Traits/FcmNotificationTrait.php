<?php


namespace App\Http\Traits;

use App\Services\notifications\FirebaseService;
use Google\Client;
use Illuminate\Support\Facades\Log as FacadesLog;
use Storage;
use Log;

trait FcmNotificationTrait
{
  protected $firebaseService;

  public function __construct(FirebaseService $firebaseService) {
    $this->firebaseService = $firebaseService;
  }

  /**
   * @param $title
   * @param $body
   * @param $tokens
   * @return bool|string
   */
  public function pushFcmNotification(string $title, string $body, array $tokens, $click_action = '', $data = '',string $notificationId='',string $notificationLogId='', $operating_system = 'android')
  {

    #prep the bundle
    $notification_object = array(
      'title' => $title,
      'body' => $body,
    );

    $data = array(
      'title' => $title,
      'body' => $body,
      'notificationLogId' => $notificationLogId,
      'type' => $type ?? 'notification type',
      'status' => $status ?? 'notification status',
      'click_action' => $click_action ?? 'test',
      'sound' => 'default',
      'badge'=> '1',
      'icon' => 'logo',
    );

    $client = new Client();
    $client->setAuthConfig(storage_path(config('services.fcm.credentials')));
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

    $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];
    if (!$accessToken) {
        return response()->json(['Error' => 'Failed to retrieve access token.'], 500);
    }

    $headers = array(
      "Authorization: Bearer $accessToken",
      'Content-Type: application/json'
    );


    $result = '';
    foreach($tokens as $token) {



      switch ($operating_system) {
          case 'ios':
            $payload = [
              'message' => [
                  'token' => $token, // Single token string for one device
                  'data' => $data,
                  'notification' => $notification_object,
              ],
            ];
              break;

        default: // Default to Android payload
            $payload = [
              'message' => [
                  'token' => $token, // Single token string for one device
                  'notification' => $notification_object,
                  'data' => $data,
              ],
            ];
            if ($operating_system !== 'android') {
              FacadesLog::warning("Unsupported operating system: $operating_system. Defaulting to Android payload. Token: $token");
            }
            break;
      }

      #Send Response To FireBase Server
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, config('services.fcm.url'));
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
      $result .= curl_exec($ch);

      curl_close($ch);
    }
    return $result;





    // $test_tokens = ['fVBXK-g4Zkvsj6oeZHeq6u:APA91bHkzauRfQRPMeN8vaNj1pKoIGkHmWhfJFaS7r8fqFtaNjGVbJtnQRvgXK9-09JuG5W_shVZqIG44-c4NRMaSoHDW3kTSEYkHrahFRzrqiuIh_zkNtKR_kF1Iy6fVNnCzKLlEzgp'];
    // $response = $this->firebaseService->sendNotification($tokens, $title, $body);

    // return response()->json($response);


    // $headers = array(
    //   'Authorization: key=AAAA4O_NHsk:APA91bGsRe6Y0fQ2aWUJL1tepZN1Lm6nZW3suJzEtgNZFjLs4ejn2D-8Lyq9Vj6QVRypse4BZAAMEXAJqw13GrS0XOtsm5qcWFoinhQs6QrG4o1lf7Oij_f7JSZNgdhojqIWJn1i6OMH',
    //   'Content-Type: application/json'
    // );

    // #Send Response To FireBase Server
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    // curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    // $result = curl_exec($ch);
    // curl_close($ch);
    // return $result;
  }
}
