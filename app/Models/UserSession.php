<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;
    protected $table = 'user_sessions';

    // The attributes that are mass assignable
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'type',
        'img',
        'country',
    ];



}
