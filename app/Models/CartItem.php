<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'color',
        'size',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPriceWithTaxAttribute(): int
    {
        $price = $this->product->price ?? 0;
        return (int) round($price * 1.11);
    }

    public function getSubtotalAttribute(): int
    {
        return $this->price_with_tax * $this->quantity;
    }
}