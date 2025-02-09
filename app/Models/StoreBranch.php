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
        'city',
        'country',
        'status',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
