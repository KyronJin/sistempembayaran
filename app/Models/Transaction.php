<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'cashier_id',
        'cashier_name',
        'member_id',
        'subtotal',
        'discount',
        'member_discount',
        'tax',
        'total',
        'points_earned',
        'points_used',
        'payment_method',
        'payment_amount',
        'change_amount',
        'status',
        'transaction_date',
        'ip_address',
        'user_agent',
        'transaction_notes',
        'verified_at',
        'verified_by',
        'void_reason',
        'voided_by',
        'voided_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'member_discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'points_earned' => 'integer',
        'points_used' => 'integer',
        'transaction_date' => 'datetime',
        'verified_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    /**
     * Get the cashier that owns the transaction.
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Get the member that owns the transaction.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the transaction details for the transaction.
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Get the points history for the transaction.
     */
    public function pointsHistory()
    {
        return $this->hasMany(PointsHistory::class);
    }

    /**
     * Get the payment splits for the transaction.
     */
    public function payments()
    {
        return $this->hasMany(TransactionPayment::class);
    }

    /**
     * Get the refunds for this transaction.
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class, 'original_transaction_id');
    }

    /**
     * Get the user who voided the transaction.
     */
    public function voidedBy()
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    /**
     * Get the user who verified the transaction.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode()
    {
        do {
            $code = 'TRX' . date('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('transaction_code', $code)->exists());

        return $code;
    }

    /**
     * Calculate points based on total
     */
    public static function calculatePoints($total)
    {
        $conversionRate = Setting::getValue('points_conversion_rate', 10000);
        return floor($total / $conversionRate);
    }

    /**
     * Convert points to rupiah
     */
    public static function pointsToRupiah($points)
    {
        $pointValue = Setting::getValue('points_to_rupiah', 1000);
        return $points * $pointValue;
    }
}
