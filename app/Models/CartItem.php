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

    // ─── Relasi ke User ───────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Relasi ke Product ────────────────────────────────────
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ─── Accessor: harga satuan (sudah termasuk PPN 11%) ──────
    public function getPriceWithTaxAttribute(): int
    {
        $price = $this->product->price ?? 0;
        return (int) round($price * 1.11);
    }

    // ─── Accessor: subtotal item ini ──────────────────────────
    public function getSubtotalAttribute(): int
    {
        return $this->price_with_tax * $this->quantity;
    }
}