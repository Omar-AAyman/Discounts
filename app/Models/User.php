<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'is_online',
        'uuid',
        'type',
        'seller_type_id',
        'city',
        'phone2',
        'facebook',
        'instagram',
        'tiktok',
        'is_sponser',
        'points',
        'is_admin',
        'img',
        'country',
        'fcm_token',
        'merchant_type', //for sellers
        'representative_type', //for delegates
        'region', // for delegates
        'is_seller',
    ];

    public function getImgAttribute($value)
    {
        return $this->getImagePath($value, 'userImages');
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
        // Check if image exists, then return the full URL
        if ($imageName) {
            return url("images/{$folder}/{$imageName}");
        }

        return url("images/{$folder}/userr.png"); // Provide a default image if none is set
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'cityRelation',
        'countryRelation'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $appends = [
        'fullname',
        'country_name',
        'country_name_ar',
        'city_name',
        'city_name_ar',
    ];

    
    public function countryRelation()
    {
        return $this->belongsTo(Country::class, 'country'); // 'country' is the FK column in stores
    }

    public function getCountryAttribute()
    {
        return $this->attributes['country'];
    }
    public function getCountryNameAttribute()
    {
        return $this->countryRelation ? $this->countryRelation->name : null;
    }
    public function getCountryNameArAttribute()
    {
        return $this->countryRelation ? $this->countryRelation->name_ar : null;
    }

    public function getCityAttribute()
    {
        return $this->attributes['city'];
    }
    public function cityRelation()
    {
        return $this->belongsTo(City::class, 'city'); // 'city' is the FK column in stores
    }
    public function getCityNameAttribute()
    {
        return $this->cityRelation ? $this->cityRelation->name : null;
    }
    public function getCityNameArAttribute()
    {
        return $this->cityRelation ? $this->cityRelation->name_ar : null;
    }


    public function stores()
    {
        return $this->hasMany(Store::class);
    }
    public function store()
    {
        return $this->hasOne(Store::class, 'user_id', 'id');
    }
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function delegateStores()
    {
        return $this->hasMany(Store::class, 'delegate_id');
    }
    public function sellerType()
    {
        return $this->hasOne(SellerType::class, 'id', 'seller_type_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function getFullnameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }


    public function lastSessionBeforeCurrent()
    {
        return $this->hasMany(UserActivity::class)
            ->orderBy('last_activity', 'desc')
            ->skip(1) // Skip the most recent session (current one)
            ->first();
    }
}
