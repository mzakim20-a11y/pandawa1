<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'discount_amount',
        'discount_type',
        'min_order',
        'is_active'
    ];

    protected $casts = [
        'discount_amount' => 'float',
        'min_order' => 'float',
        'is_active' => 'boolean'
    ];
}
