<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkincareRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'consultation_id',
        'routine_type',
        'day_of_week',
        'cycling_phase',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RoutineItem::class, 'routine_id')->orderBy('step_order');
    }
}
