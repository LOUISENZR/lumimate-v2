<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IngredientConflict extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id_1',
        'ingredient_id_2',
        'risk_level',
        'explanation',
        'solution',
        'reference_source',
    ];

    public function ingredient1(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id_1');
    }

    public function ingredient2(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id_2');
    }

    public function isRisky(): bool
    {
        return $this->risk_level === 'risky';
    }

    public function isCaution(): bool
    {
        return $this->risk_level === 'caution';
    }

    public function isSafe(): bool
    {
        return $this->risk_level === 'safe';
    }

    public function isRecommended(): bool
    {
        return $this->risk_level === 'recommended';
    }
}
