<?php

namespace App\Models;

use App\Enums\Currency;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'lahza_transaction_id',
        'reference',
        'amount',
        'currency',
        'status',
        'payment_method',
        'channel',
        'ip_address',
        'authorization_code',
        'card_type',
        'last4',
        'bank',
        'metadata',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'metadata' => 'array',
        'status' => TransactionStatus::class,
        'currency' => Currency::class,
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeCurrency($query, $currency)
    {
        return $query->where('currency', strtoupper($currency));
    }

    // Encryption for sensitive fields
    protected function authorizationCode(): Attribute
    {
        return Attribute::make(
            get: fn($value) => decrypt($value),
            set: fn($value) => encrypt($value),
        );
    }
}
