<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $table = 'options';
    protected $fillable = ['key', 'value', 'img'];


    /**
     * Accessor for the 'img' attribute
     */
    public function getImgAttribute($value)
    {
        return $this->getImagePath($value, 'optionImages');
    }

    /**
     * Helper method to get the image path with folder prefix
     *
     * @param string|null $imageName
     * @param string $folder
     * @return string|null
     */
    private function getImagePath($imageName, $folder)
    {
        if ($imageName) {
            return url("images/{$folder}/{$imageName}");
        }
        return null;
    }
}
