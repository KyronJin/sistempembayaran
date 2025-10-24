<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'discount_value',
        'member_only',
        'start_date',
        'end_date',
        'is_active',
        'image',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'member_only' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Check if promotion is currently active
     */
    public function isCurrentlyActive()
    {
        $now = now()->toDateString();
        return $this->is_active 
            && $this->start_date <= $now 
            && $this->end_date >= $now;
    }

    /**
     * Get active promotions
     */
    public static function getActivePromotions($memberOnly = false)
    {
        $query = self::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());

        if ($memberOnly) {
            $query->where('member_only', true);
        }

        return $query->get();
    }
}
