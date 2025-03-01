<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price_before_discount',
        'discount_percentage',
        'discount_amount',
        'store_id',
        'is_online',
        'bg_img',
        'period',
        'exclusions',
    ];



    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getBgImgAttribute($value)
    {
        return $this->getImagePath($value, 'offerImages');
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
