<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    protected $table = 'members';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'total_orders',
        'total_spent',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'total_orders' => 'integer',
        'total_spent'  => 'float',
        'password'     => 'hashed',
    ];

    // Nilai awal untuk member yang daftar sendiri lewat halaman /register
    protected $attributes = [
        'total_orders' => 0,
        'total_spent'  => 0,
        'status'       => 'active',
    ];
}