<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class Store extends Model
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

    protected $table = 'stores';
    protected $fillable = [
        'name',
        'user_id',
        'section_id',
        'description',
        'is_online',
        'discount_percentage',
        'uuid',
        'delegate_id',
        'licensed_operator_number',
        'sector_representative',
        'location',
        'work_days',
        'work_hours',
        'sector_qr',
        'contract_img',
        'store_img',
        'status',
        'points',
        'seller_name',
        'email',
        'phone_number2',
        'facebook',
        'instagram',
        'tiktok',
        'phone_number1',
        'is_most_popular',
        'city',
        'country',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegate_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function branches()
    {
        return $this->hasMany(StoreBranch::class, 'store_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Add custom accessors to modify how images are accessed
    public function getContractImgAttribute($value)
    {
        return $this->getImagePath($value, 'contractImages');
    }

    public function getStoreImgAttribute($value)
    {
        return $this->getImagePath($value, 'storeImages');
    }

    public function getSectoreQrAttribute($value)
    {
        return $this->getImagePath($value, 'qrcodes');
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
