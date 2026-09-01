<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_name',
        'slug',
        'category',
        'function',
        'usage_time',
        'max_frequency',
        'irritation_level',
        'safe_pregnancy',
        'reference_source',
    ];

    protected $casts = [
        'max_frequency' => 'integer',
        'safe_pregnancy' => 'boolean',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredients')
                    ->withPivot('is_key_active')
                    ->withTimestamps();
    }

    public function userProducts(): BelongsToMany
    {
        return $this->belongsToMany(UserProduct::class, 'user_product_ingredients')
                    ->withTimestamps();
    }

    public function conflictsAsFirst(): HasMany
    {
        return $this->hasMany(IngredientConflict::class, 'ingredient_id_1');
    }

    public function conflictsAsSecond(): HasMany
    {
        return $this->hasMany(IngredientConflict::class, 'ingredient_id_2');
    }

    /**
     * Check conflict with another ingredient
     */
    public function getConflictWith(int|Ingredient $otherIngredient): ?IngredientConflict
    {
        $otherId = $otherIngredient instanceof Ingredient ? $otherIngredient->id : $otherIngredient;

        return IngredientConflict::where(function ($query) use ($otherId) {
            $query->where('ingredient_id_1', $this->id)
                  ->where('ingredient_id_2', $otherId);
        })->orWhere(function ($query) use ($otherId) {
            $query->where('ingredient_id_1', $otherId)
                  ->where('ingredient_id_2', $this->id);
        })->first();
    }
}
