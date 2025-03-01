<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'parent_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to fetch replies (responses) for a ticket.
     */
    public function responses()
    {
        return $this->hasMany(Ticket::class, 'parent_id')
        ->select(['title', 'body', 'parent_id', 'created_at']);
    }

    /**
     * Relationship to fetch the parent ticket (if this is a response).
     */
    public function parent()
    {
        return $this->belongsTo(Ticket::class, 'parent_id');
    }
}
