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
    protected $appends = [
        'super_cost_per_month',
        'basic_cost_per_month',
        'elite_cost_per_month',
    ];
    
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'package_section');
    }
    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }



    public function getPackageCostPerMonth($packageName)
    {
        $packageKey = strtolower($packageName) . '_cost_per_month';
        $option = Option::where('key', $packageKey)->first();

        // Define default prices
        $defaultPrices = [
            'super' => 59.99,
            'basic' => 20.99,
            'elite' => 70.99,
        ];

        return $option ? floatval($option->value) : ($defaultPrices[$packageName] ?? 0);
    }

    // Custom Accessors for Attributes
    public function getSuperCostPerMonthAttribute()
    {
        return $this->getPackageCostPerMonth('super');
    }

    public function getBasicCostPerMonthAttribute()
    {
        return $this->getPackageCostPerMonth('basic');
    }

    public function getEliteCostPerMonthAttribute()
    {
        return $this->getPackageCostPerMonth('elite');
    }

}
