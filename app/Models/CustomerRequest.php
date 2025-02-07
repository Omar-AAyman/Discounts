<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRequest extends Model
{
    use HasFactory;
    protected $table = 'customer_requests';
    protected $fillable = ['user_id','status','product_id'];

    public function user(){
        return $this->belongsTo(Offer::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
