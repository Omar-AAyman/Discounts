<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'old_discount_percentage',
        'requested_discount_percentage',
        'status',
        'rejection_reason',
        'reviewed_by'
    ];

    public function discountable()
    {
        return $this->morphTo();
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
