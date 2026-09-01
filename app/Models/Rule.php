<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_code',
        'rule_type',
        'conditions',
        'actions',
        'certainty_factor',
        'explanation',
        'reference_source',
        'is_active',
    ];

    protected $casts = [
        'conditions' => 'array',
        'actions' => 'array',
        'certainty_factor' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Scope active rules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if a set of facts matches this rule's conditions
     */
    public function matches(array $facts): bool
    {
        if (empty($this->conditions)) {
            return false;
        }

        foreach ($this->conditions as $key => $expectedValue) {
            if ($key === 'skin_type_not') {
                if (isset($facts['skin_type']) && $facts['skin_type'] === $expectedValue) {
                    return false;
                }
                continue;
            }

            if ($key === 'experience_level_not') {
                if (isset($facts['experience_level']) && $facts['experience_level'] === $expectedValue) {
                    return false;
                }
                continue;
            }

            if ($key === 'concern') {
                $concerns = $facts['skin_concerns'] ?? [];
                if (!in_array($expectedValue, $concerns)) {
                    return false;
                }
                continue;
            }

            if ($key === 'has_ingredient') {
                $ingredients = $facts['ingredients'] ?? [];
                if (!in_array($expectedValue, $ingredients)) {
                    return false;
                }
                continue;
            }

            // Standard fact equality check
            if (!isset($facts[$key]) || $facts[$key] != $expectedValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * Combine two Certainty Factors using CF formula:
     * CF_combined = CF1 + CF2 * (1 - CF1)
     */
    public static function combineCF(float $cf1, float $cf2): float
    {
        return round($cf1 + ($cf2 * (1.0 - $cf1)), 4);
    }
}
