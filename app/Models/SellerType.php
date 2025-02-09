<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerType extends Model
{
    use HasFactory;

    protected $table = 'seller_types';
    protected $fillable = [
        'name',
        'ar_description',
        'en_description',
    ];
}
