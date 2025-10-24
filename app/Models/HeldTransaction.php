<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeldTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'hold_code',
        'cashier_id',
        'member_id',
        'cart_items',
        'points_to_use',
        'notes',
        'held_at',
    ];

    protected $casts = [
        'cart_items' => 'array',
        'points_to_use' => 'integer',
        'held_at' => 'datetime',
    ];

    /**
     * Get the cashier that holds the transaction.
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Get the member (if any).
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Generate unique hold code
     */
    public static function generateHoldCode()
    {
        do {
            $code = 'HOLD' . date('Ymd') . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        } while (self::where('hold_code', $code)->exists());

        return $code;
    }
}
