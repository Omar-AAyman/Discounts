<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'user_id',
        'status',
        'sector_name',
        'licensed_operator_number',
        'sector_representative',
        'location',
        'phone',
        'phone2',
        'email',
        'work_days',
        'work_hours',
        'facebook',
        'instagram',
        'sector_qr',
        'contract_img',
        'sector_img',
    ];
    
    public function user()
{
    return $this->belongsTo(User::class);
}

}
