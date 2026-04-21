<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_province', 'shipping_postal_code',
        'courier', 'shipping_cost', 'subtotal', 'discount', 'total',
        'voucher_code', 'status', 'payment_status', 'payment_method',
        'payment_reference', 'paid_at', 'notes',
    ];

    protected $casts = ['paid_at' => 'datetime'];

    public function user()   { return $this->belongsTo(User::class); }
    public function items()  { return $this->hasMany(OrderItem::class); }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp' . number_format($this->total, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'    => '<span class="badge-yellow">Pending</span>',
            'confirmed'  => '<span class="badge-blue">Confirmed</span>',
            'processing' => '<span class="badge-blue">Processing</span>',
            'shipped'    => '<span class="badge-purple">Shipped</span>',
            'delivered'  => '<span class="badge-green">Delivered</span>',
            'cancelled'  => '<span class="badge-red">Cancelled</span>',
            'refunded'   => '<span class="badge-gray">Refunded</span>',
            default      => $this->status,
        };
    }
}