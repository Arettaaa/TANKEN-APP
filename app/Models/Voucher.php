<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'quota',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
         'is_welcome',

    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
        'is_welcome' => 'boolean',
    ];

    public function userVouchers()
    {
        return $this->hasMany(UserVoucher::class);
    }

    // Sisa kuota yang belum diklaim
    public function getRemainingQuotaAttribute(): ?int
    {
        if (is_null($this->quota)) return null;
        return max(0, $this->quota - $this->userVouchers()->count());
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && now()->gt($this->expires_at);
    }
}