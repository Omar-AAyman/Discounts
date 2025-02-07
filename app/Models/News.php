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
}
