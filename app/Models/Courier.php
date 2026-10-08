<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courier extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'vehicle',
        'plate_number',
        'rating',
        'total_trips',
        'avatar',
        'status',
        'badge'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
