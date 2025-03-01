<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotificationUserLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'push_notification_id',
        'user_id',
        'is_viewed',
        'viewed_at'
    ];

    public function pushNotification()
    {
        return $this->belongsTo(PushNotification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
