<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsHistory extends Model
{
    use HasFactory;

    protected $table = 'points_history';

    protected $fillable = [
        'member_id',
        'transaction_id',
        'points',
        'type',
        'description',
        'date',
    ];

    protected $casts = [
        'points' => 'integer',
        'date' => 'datetime',
    ];

    /**
     * Get the member that owns the points history.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the transaction that owns the points history.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
