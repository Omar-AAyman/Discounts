<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'en_title',
        'ar_title',
        'en_description',
        'ar_description',
        'type',
        'is_active'
    ];

    public function userLogs()
    {
        return $this->hasMany(PushNotificationUserLog::class);
    }
}
