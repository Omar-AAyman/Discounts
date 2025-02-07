<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class Package extends Model
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

    protected $table = 'packages';
    protected $fillable = ['name','uuid','is_online','description'];

    public function sections(){
        return $this->hasMany(Section::class);
    }

    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }
                        
}
