<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_code',
        'qr_code',
        'points',
        'join_date',
        'birthdate',
        'status',
    ];

    protected $casts = [
        'points' => 'integer',
        'join_date' => 'date',
        'birthdate' => 'date',
    ];

    /**
     * Get the user that owns the member.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for the member.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the points history for the member.
     */
    public function pointsHistory()
    {
        return $this->hasMany(PointsHistory::class);
    }

    /**
     * Get the vouchers for the member.
     */
    public function vouchers()
    {
        return $this->hasMany(MemberVoucher::class);
    }

    /**
     * Add points to member
     */
    public function addPoints($points, $transactionId = null, $description = null)
    {
        $this->points += $points;
        $this->save();

        PointsHistory::create([
            'member_id' => $this->id,
            'transaction_id' => $transactionId,
            'points' => $points,
            'type' => 'earned',
            'description' => $description ?? 'Points earned from transaction',
            'date' => now(),
        ]);

        return $this;
    }

    /**
     * Use points from member
     */
    public function usePoints($points, $transactionId = null, $description = null)
    {
        if ($this->points < $points) {
            throw new \Exception('Insufficient points');
        }

        $this->points -= $points;
        $this->save();

        PointsHistory::create([
            'member_id' => $this->id,
            'transaction_id' => $transactionId,
            'points' => -$points,
            'type' => 'used',
            'description' => $description ?? 'Points used for transaction',
            'date' => now(),
        ]);

        return $this;
    }

    /**
     * Check if member is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Generate unique member code
     */
    public static function generateMemberCode()
    {
        do {
            $code = 'MBR' . date('Y') . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('member_code', $code)->exists());

        return $code;
    }
}
