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
        'status','points',
        'seller_name','email','phone_number2','facebook','instagram','phone_number1',
        'is_most_popular', 'city','country',
    ];
    

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function delegate(){
        return $this->belongsTo(User::class,'delegate_id');
    }

    public function offers(){
        return $this->hasMany(Offer::class);
    }




}
