<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    public $fillable = ['user_id','amount','amount_after_discount','status','store_id','subscription_id',
    'type' , 'product_id','quantity'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function subscription(){
        return $this->belongsTo(Subscription::class);
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

        /**
     * Query scope to filter paid product invoices.
     */
    public function paidProducts($query)
    {
        return $query->where('status', 'paid')
        ->where('type', 'product')
        ->with('product');
    }

}
