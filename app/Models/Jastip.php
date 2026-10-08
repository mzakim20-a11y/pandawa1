<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jastip extends Model
{
    protected $fillable = [
        'order_id',
        'store_name',
        'store_address',
        'destination_address',
        'shopping_notes',
        'talangan_amount',
        'jastip_fee'
    ];

    protected $casts = [
        'talangan_amount' => 'float',
        'jastip_fee' => 'float'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
