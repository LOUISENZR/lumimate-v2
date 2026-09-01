<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoutineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'routine_id',
        'user_product_id',
        'step_order',
        'category',
        'usage_instructions',
    ];

    protected $casts = [
        'step_order' => 'integer',
    ];

    public function routine(): BelongsTo
    {
        return $this->belongsTo(SkincareRoutine::class, 'routine_id');
    }

    public function userProduct(): BelongsTo
    {
        return $this->belongsTo(UserProduct::class);
    }

    public function dailyTrackers(): HasMany
    {
        return $this->hasMany(DailyTracker::class);
    }
}
