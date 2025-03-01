<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBranch extends Model
{
    use HasFactory;

    protected $table = 'store_branches';

    protected $fillable = [
        'store_id',
        'status',
        'city',
        'country',
    ];


    protected $appends = [
        'country_name',
        'country_name_ar',
        'city_name',
        'city_name_ar',
    ];
    protected $hidden = [
        'countryRelation',
        'cityRelation',
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
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
