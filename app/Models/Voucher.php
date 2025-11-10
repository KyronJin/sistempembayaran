<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'points_required',
        'discount_type',
        'discount_value',
        'min_transaction',
        'max_discount',
        'valid_from',
        'valid_until',
        'max_usage',
        'max_usage_per_member',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_transaction' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    // Relationship dengan member vouchers
    public function memberVouchers()
    {
        return $this->hasMany(MemberVoucher::class);
    }

    // Helper methods
    public function isValid()
    {
        return $this->is_active 
            && now()->between($this->valid_from, $this->valid_until)
            && ($this->stock === null || $this->stock > 0);
    }

    public function getDiscountAmount($transactionAmount)
    {
        if ($this->discount_type === 'percentage') {
            $discount = $transactionAmount * ($this->discount_value / 100);
            return $this->max_discount ? min($discount, $this->max_discount) : $discount;
        }
        return $this->discount_value;
    }
}
