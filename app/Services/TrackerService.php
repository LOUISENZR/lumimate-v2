<?php

namespace App\Services;

use App\Models\DailyTracker;
use App\Models\RoutineItem;
use App\Models\SkinProgressLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TrackerService
{
    /**
     * Get or create today's checklist for a user based on active routine
     *
     * @param User $user
     * @param string|null $date (Y-m-d)
     * @return array
     */
    public function getDailyChecklist(User $user, ?string $date = null): array
    {
        $targetDate = $date ? Carbon::parse($date) : Carbon::today();
        $dateString = $targetDate->toDateString();
        $dayOfWeek = strtolower($targetDate->englishDayOfWeek); // e.g. 'monday'

        $routines = $user->skincareRoutines()
            ->with(['items.userProduct.product'])
            ->get();

        // 1. Morning Routine items
        $morningRoutine = $routines->firstWhere('routine_type', 'morning');
        $morningItems = $morningRoutine ? $morningRoutine->items : collect();

        // 2. Night Routine items (Standard or Skin Cycling matching current day)
        $nightRoutine = $routines->first(function ($r) use ($dayOfWeek) {
            if ($r->routine_type === 'night') return true;
            if ($r->routine_type === 'skin_cycling') {
                if ($r->day_of_week === 'all') return true;
                if ($r->cycling_phase === 'exfoliation' && in_array($dayOfWeek, ['monday', 'thursday'])) return true;
                if ($r->cycling_phase === 'retinoid' && in_array($dayOfWeek, ['tuesday', 'friday'])) return true;
                if ($r->cycling_phase === 'recovery' && in_array($dayOfWeek, ['wednesday', 'saturday', 'sunday'])) return true;
            }
            return false;
        });

        $nightItems = $nightRoutine ? $nightRoutine->items : collect();

        // Sync or get existing DailyTracker records for today
        $allItemIds = $morningItems->pluck('id')->merge($nightItems->pluck('id'))->unique();

        $existingTrackers = DailyTracker::where('user_id', $user->id)
            ->where('tracked_date', $dateString)
            ->whereIn('routine_item_id', $allItemIds)
            ->get()
            ->keyBy('routine_item_id');

        $formatItems = function (Collection $items) use ($user, $dateString, $existingTrackers) {
            return $items->map(function (RoutineItem $item) use ($user, $dateString, $existingTrackers) {
                $tracker = $existingTrackers->get($item->id);

                return [
                    'routine_item_id' => $item->id,
                    'step_order' => $item->step_order,
                    'category' => $item->category,
                    'product_name' => $item->userProduct->display_name,
                    'brand_name' => $item->userProduct->display_brand,
                    'usage_instructions' => $item->usage_instructions,
                    'is_completed' => $tracker ? (bool) $tracker->is_completed : false,
                    'completed_at' => $tracker?->completed_at?->format('H:i'),
                ];
            });
        };

        $morningFormatted = $formatItems($morningItems);
        $nightFormatted = $formatItems($nightItems);

        $totalSteps = $morningFormatted->count() + $nightFormatted->count();
        $completedSteps = $morningFormatted->where('is_completed', true)->count() + $nightFormatted->where('is_completed', true)->count();
        $completionRate = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;

        return [
            'date' => $dateString,
            'day_name' => $targetDate->translatedFormat('l, d F Y'),
            'night_phase' => $nightRoutine?->cycling_phase ?? 'daily',
            'night_notes' => $nightRoutine?->notes,
            'morning_checklist' => $morningFormatted->values(),
            'night_checklist' => $nightFormatted->values(),
            'total_steps' => $totalSteps,
            'completed_steps' => $completedSteps,
            'completion_rate' => $completionRate,
            'current_streak' => DailyTracker::calculateStreak($user->id),
        ];
    }

    /**
     * Toggle completion status of a routine item for a given date
     */
    public function toggleItemCompletion(User $user, int $routineItemId, ?string $date = null): DailyTracker
    {
        $targetDate = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

        $tracker = DailyTracker::firstOrNew([
            'user_id' => $user->id,
            'routine_item_id' => $routineItemId,
            'tracked_date' => $targetDate,
        ]);

        $tracker->is_completed = !$tracker->is_completed;
        $tracker->completed_at = $tracker->is_completed ? Carbon::now() : null;
        $tracker->save();

        return $tracker;
    }

    /**
     * Get Chart.js formatted statistics for past N days
     */
    public function getChartStatistics(User $user, int $days = 7): array
    {
        $labels = [];
        $percentages = [];
        $startDate = Carbon::today()->subDays($days - 1);

        for ($i = 0; $i < $days; $i++) {
            $current = $startDate->copy()->addDays($i);
            $dateStr = $current->toDateString();
            $labels[] = $current->translatedFormat('D, d M');

            $total = DailyTracker::where('user_id', $user->id)->where('tracked_date', $dateStr)->count();
            $done = DailyTracker::where('user_id', $user->id)->where('tracked_date', $dateStr)->where('is_completed', true)->count();

            $percentages[] = $total > 0 ? round(($done / $total) * 100) : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Konsistensi Rutinitas (%)',
                    'data' => $percentages,
                    'borderColor' => '#4D0E12', // Sceptre Red
                    'backgroundColor' => 'rgba(216, 183, 184, 0.3)', // Dusty Rose transparent
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'streak' => DailyTracker::calculateStreak($user->id),
            'overall_consistency' => DailyTracker::calculateConsistencyPercentage($user->id, 30),
        ];
    }
}
