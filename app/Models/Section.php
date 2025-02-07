<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class Section extends Model
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
    protected $table = 'sections';
    protected $fillable = ['name','type','package_id','uuid','is_online','description'];
    
    public function package(){
        return $this->belongsTo(Package::class);
    }

    public function stores(){
        return $this->hasMany(Store::class);
    }
}
