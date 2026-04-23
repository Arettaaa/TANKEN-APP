<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'type',
        'price', 'original_price', 'sku', 'is_active', 'is_featured',
        'main_image', 'size_chart_image', 'gallery', 'colors', 'sizes', 'rating', 'review_count',
    ];

    protected $casts = [
        'gallery'        => 'array',
        'colors'         => 'array',
        'sizes'          => 'array',
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
    ];

    // ---- Auto-generate slug ----
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // ---- Relationships ----
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ---- Helpers ----
    public function getTotalStockAttribute(): int
    {
        return $this->stocks()->sum('quantity');
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->original_price && $this->original_price > $this->price) {
            return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
        }
        return 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp' . number_format($this->price, 0, ',', '.');
    }

    public function getMainImageUrlAttribute(): string
    {
        return $this->main_image
            ? asset('storage/' . $this->main_image)
            : asset('images/placeholder.jpg');
    }

    public function isLowStock(int $threshold = 10): bool
    {
        return $this->total_stock <= $threshold;
    }
}