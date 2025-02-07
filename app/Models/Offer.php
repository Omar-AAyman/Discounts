<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['description','discount_percentage' ,
    'store_id','is_online','bg_img','period','exclusions',
    ];

   

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    
}
