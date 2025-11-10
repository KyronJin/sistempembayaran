<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MemberVoucher extends Model
{
    protected $fillable = [
        'member_id',
        'voucher_id',
        'voucher_code',
        'points_used',
        'redeemed_at',
        'expires_at',
        'status',
        'transaction_id',
        'used_at',
    ];

    protected $casts = [
        'redeemed_at' => 'date',
        'expires_at' => 'date',
        'used_at' => 'datetime',
    ];

    // Auto-generate unique voucher code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($memberVoucher) {
            if (empty($memberVoucher->voucher_code)) {
                $memberVoucher->voucher_code = self::generateUniqueCode();
            }
        });
    }

    // Generate unique voucher code
    private static function generateUniqueCode()
    {
        do {
            $code = 'VC' . strtoupper(Str::random(8));
        } while (self::where('voucher_code', $code)->exists());
        
        return $code;
    }

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'available' 
            && now()->lte($this->expires_at);
    }

    public function markAsUsed($transactionId)
    {
        $this->update([
            'status' => 'used',
            'transaction_id' => $transactionId,
            'used_at' => now(),
        ]);
    }
}
