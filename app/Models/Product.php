<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'store_id',
        'offer_id',
        'price',
        'price_before_discount',
        'discount_percentage',
        'discount_amount',
        'is_excluded_from_discount',
        'img',
        'is_online',
    ];

    /**
     * Relationships.
     */

    // Define the relationship with the Store model
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // Define the relationship with the Offer model
    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
}
