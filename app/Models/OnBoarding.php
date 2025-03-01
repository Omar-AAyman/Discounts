<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnBoarding extends Model
{
    use HasFactory;

    protected $table = 'on_boardings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slide_id',
        'image_url',
        'title',
        'subtitle',
        'textbutton',
        'order',
    ];

    /**
     * Get the image URL for the onboarding image.
     *
     * @param string $value
     * @return string|null
     */
    public function getImageUrlAttribute($value)
    {
        return $this->getImagePath($value, 'onBoardingImages');
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
