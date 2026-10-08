<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'sender_name',
        'sender_phone',
        'sender_address',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'package_type',
        'package_note',
        'armada_type',
        'distance_km'
    ];

    protected $casts = [
        'distance_km' => 'float'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
