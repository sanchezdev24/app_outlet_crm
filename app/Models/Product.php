<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'stock',
        'min_stock',
        'category_id',
        'brand_id',
        'images',
        'discount_percentage',
        'discount_valid_until',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'images' => 'array',
        'discount_percentage' => 'decimal:2',
        'discount_valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->discount_percentage && $this->isDiscountValid()) {
            return $this->price * (1 - $this->discount_percentage / 100);
        }
        return $this->price;
    }

    public function isDiscountValid()
    {
        if (!$this->discount_valid_until) {
            return true;
        }
        return now() <= $this->discount_valid_until;
    }
}