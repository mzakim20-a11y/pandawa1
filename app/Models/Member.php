<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'total_orders',
        'total_spent',
        'status'
    ];

    protected $casts = [
        'total_orders' => 'integer',
        'total_spent' => 'float'
    ];
}
