<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    protected $fillable = ['user_id','package_id','is_online','status','type','period_in_months'];

    public function package(){
        return $this->belongsTo(Package::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
