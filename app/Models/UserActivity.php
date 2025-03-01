<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
    protected $table = 'user_activity';

    protected $fillable = ['user_id', 'session_token', 'last_activity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getLastActivityAttribute($value)
    {
        return Carbon::parse($value)->addHours(2);
    }
}
