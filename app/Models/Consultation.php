<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skin_type',
        'skin_concerns',
        'sensitivity_level',
        'experience_level',
        'retinol_tolerance',
        'is_pregnant',
        'special_conditions',
        'raw_answers',
    ];

    protected $casts = [
        'skin_concerns' => 'array',
        'special_conditions' => 'array',
        'raw_answers' => 'array',
        'is_pregnant' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function routines(): HasMany
    {
        return $this->hasMany(SkincareRoutine::class);
    }
}
