<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_id', 'order_id', 'customer_name', 'amount', 
        'method', 'status', 'transaction_id'
    ];

    // Relasi ke tabel Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}