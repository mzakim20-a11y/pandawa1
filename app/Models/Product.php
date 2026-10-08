<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'original_price',
        'rating',
        'sold_count',
        'prep_time',
        'image',
        'badge',
        'is_popular',
        'is_available'
    ];

    protected $casts = [
        'price' => 'float',
        'original_price' => 'float',
        'rating' => 'float',
        'is_popular' => 'boolean',
        'is_available' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
