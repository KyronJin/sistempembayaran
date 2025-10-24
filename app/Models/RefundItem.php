<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_id',
        'transaction_detail_id',
        'quantity',
        'refund_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'refund_amount' => 'decimal:2',
    ];

    /**
     * Get the refund that owns the item.
     */
    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    /**
     * Get the original transaction detail.
     */
    public function transactionDetail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }
}
