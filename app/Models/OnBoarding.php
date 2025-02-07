<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnBoarding extends Model
{
    use HasFactory;

    protected $table = 'on_boardings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slide_id',
        'image_url',
        'title',
        'subtitle',
        'textbutton',
        'order',
    ];
}
