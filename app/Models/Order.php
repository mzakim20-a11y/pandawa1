<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'member_id',
        'order_code',
        'order_type',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
        'courier_id',
        'subtotal',
        'shipping_cost',
        'service_fee',
        'discount',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'current_step'
    ];

    protected $casts = [
        'subtotal' => 'float',
        'shipping_cost' => 'float',
        'service_fee' => 'float',
        'discount' => 'float',
        'total_amount' => 'float',
        'current_step' => 'integer'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    public function jastip(): HasOne
    {
        return $this->hasOne(Jastip::class);
    }
}