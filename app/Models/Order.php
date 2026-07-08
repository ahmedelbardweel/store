<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'subtotal', 'discount', 'coupon_code',
        'total_amount', 'payment_status', 'payment_method', 'payment_id', 'downloads_revoked',
    ];

    protected $casts = [
        'downloads_revoked' => 'boolean',
        'subtotal'          => 'decimal:2',
        'discount'          => 'decimal:2',
        'total_amount'      => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    public function canDownload(): bool
    {
        return $this->isCompleted() && !$this->downloads_revoked;
    }
}
