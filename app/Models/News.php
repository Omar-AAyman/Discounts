<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class News extends Model
{
    use HasFactory;

                    // generate a uuid for newly created records
                    protected static function boot()
                    {
                        parent::boot();

                        static::creating(function ($model) {
                            if (empty($model->uuid)) {
                                $model->uuid = (string) Uuid::uuid4();
                            }
                        });
                    }
    protected $table ='news';
    protected $fillable = ['title','description','img','is_online','uuid'];

    // New method to set image path
    public function getImgAttribute($value)
    {
        return $this->getImagePath($value, 'newsImages');
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
