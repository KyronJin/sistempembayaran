<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_code',
        'original_transaction_id',
        'processed_by',
        'refund_amount',
        'refund_type',
        'refund_method',
        'reason',
        'status',
        'refund_date',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'refund_date' => 'datetime',
    ];

    /**
     * Get the original transaction.
     */
    public function originalTransaction()
    {
        return $this->belongsTo(Transaction::class, 'original_transaction_id');
    }

    /**
     * Get the user who processed the refund.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the refund items.
     */
    public function items()
    {
        return $this->hasMany(RefundItem::class);
    }

    /**
     * Generate unique refund code
     */
    public static function generateRefundCode()
    {
        do {
            $code = 'RFD' . date('Ymd') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('refund_code', $code)->exists());

        return $code;
    }
}
