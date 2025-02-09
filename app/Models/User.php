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

        if ($value) {
            return asset('userImages/' . $value);
        }
        return asset('userImages/userr.png'); // Provide a default image if none is set
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function stores()
    {
        return $this->hasMany(Store::class);
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
        return $this->hasOne(SellerType::class, 'id','seller_type_id');
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
}
