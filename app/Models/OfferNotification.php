<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferNotification extends Model
{
    use HasFactory;
    protected $table = 'offer_notifications';
    protected $fillable = ['offer_id','status','new_discount_percentage'];

    public function offer(){
        return $this->belongsTo(Offer::class);
    }
}
