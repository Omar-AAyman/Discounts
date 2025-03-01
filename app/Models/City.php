<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_ar',
        'country_id',
    ];

    /**
     * Get the country that owns the city
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get created_at timestamp formatted for display
     */
    public function getCreatedAttribute(): string|null
    {
        return $this->created_at?->format('M d, Y \\a\\t H:i');
    }

    /**
     * Get updated_at timestamp formatted for display
     */
    public function getUpdatedAttribute(): string|null
    {
        return $this->updated_at?->format('M d, Y \\a\\t H:i');
    }
}
