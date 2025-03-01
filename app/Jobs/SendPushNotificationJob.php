<?php

namespace App\Jobs;

use App\Helpers\PushNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Traits\FcmNotificationTrait;
use App\Models\PushNotificationUserLog;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FcmNotificationTrait;

    public $timeout = 3600;

    public $tries = 3;

    public $user;
    public $notificationDetails;
    public $preferredLang;
    public $operating_system;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $notificationDetails, $operating_system)
    {
        $this->user = $user;
        $this->notificationDetails = $notificationDetails;
        $this->operating_system = $operating_system ?? 'android';
    }

    /**
     * Execute the job.
     */
    public function handle()
    {

        $notificationDetails = $this->notificationDetails;
        $user = $this->user;

        $language =  $user->lang ?? 'ar';
        $operating_system = $this->operating_system;
        $title = $language === 'ar' ? $notificationDetails['ar_title'] : $notificationDetails['en_title'];
        $body = $language === 'ar' ? $notificationDetails['ar_description'] : $notificationDetails['en_description'];
        // Dispatch a job to log the push notification and capture the log_id
        $notificationLogId = PushNotifications::log($user->id, (object) $notificationDetails,);

        // Proceed to push the notification
        $this->pushFcmNotification($title, $body, [$user->fcm_token], null, null, $notificationLogId, $operating_system);
    }
}
