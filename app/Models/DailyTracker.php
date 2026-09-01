<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DailyTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'routine_item_id',
        'tracked_date',
        'is_completed',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'tracked_date' => 'date',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function routineItem(): BelongsTo
    {
        return $this->belongsTo(RoutineItem::class);
    }

    /**
     * Calculate consecutive streak days for a user
     */
    public static function calculateStreak(int $userId): int
    {
        $dates = self::where('user_id', $userId)
            ->where('is_completed', true)
            ->selectRaw('DATE(tracked_date) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $streak = 0;
        $current = Carbon::today();
        
        // If today is not completed yet, check if yesterday was completed
        if (!in_array($current->toDateString(), $dates)) {
            $current = Carbon::yesterday();
            if (!in_array($current->toDateString(), $dates)) {
                return 0;
            }
        }

        while (in_array($current->toDateString(), $dates)) {
            $streak++;
            $current = $current->copy()->subDay();
        }

        return $streak;
    }

    /**
     * Calculate 30-day consistency percentage
     */
    public static function calculateConsistencyPercentage(int $userId, int $days = 30): float
    {
        $startDate = Carbon::today()->subDays($days);
        $totalItems = self::where('user_id', $userId)
            ->where('tracked_date', '>=', $startDate)
            ->count();

        if ($totalItems === 0) {
            return 0.0;
        }

        $completedItems = self::where('user_id', $userId)
            ->where('tracked_date', '>=', $startDate)
            ->where('is_completed', true)
            ->count();

        return round(($completedItems / $totalItems) * 100, 1);
    }
}
