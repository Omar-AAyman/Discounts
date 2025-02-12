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

    public function discountRequests()
    {
        return $this->morphMany(DiscountRequest::class, 'discountable');
    }

    public function getImgAttribute($value)
    {
        return $this->getImagePath($value, 'productImages');
    }

    /**
     * Helper method to get the image path with folder prefix
     *
     * @param string $imageName
     * @param string $folder
     * @return string
     */
    private function getImagePath($imageName, $folder)
    {
        // Check if image exists, then return the path
        if ($imageName) {
            // Adjust the path to work with public_html and the images folder
            return url("images/{$folder}/{$imageName}");
        }

        return null;
    }
}
