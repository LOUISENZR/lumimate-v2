<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\IngredientConflict;
use App\Models\UserProduct;
use Illuminate\Support\Collection;

class ConflictCheckerService
{
    /**
     * Analyze conflicts from a list of Ingredient IDs or Ingredient models
     *
     * @param array|Collection $ingredients
     * @return array
     */
    public function analyzeIngredients($ingredients): array
    {
        $ingredientModels = $this->resolveIngredientModels($ingredients);
        $ingredientIds = $ingredientModels->pluck('id')->toArray();

        if (count($ingredientIds) < 2) {
            return [
                'has_conflicts' => false,
                'risky_count' => 0,
                'caution_count' => 0,
                'safe_count' => 0,
                'recommended_count' => 0,
                'conflicts' => [],
                'risky' => [],
                'caution' => [],
                'safe' => [],
                'recommended' => [],
                'all_conflicts' => collect(),
                'has_retinol_and_exfoliant' => false,
                'has_vitamin_c_and_retinol' => false,
                'summary' => 'Jumlah bahan aktif kurang dari 2, tidak ditemukan potensi benturan.',
            ];
        }

        $conflicts = IngredientConflict::with(['ingredient1', 'ingredient2'])
            ->where(function ($query) use ($ingredientIds) {
                $query->whereIn('ingredient_id_1', $ingredientIds)
                      ->whereIn('ingredient_id_2', $ingredientIds);
            })
            ->get();

        $risky = [];
        $caution = [];
        $safe = [];
        $recommended = [];

        foreach ($conflicts as $conflict) {
            $formatted = [
                'id' => $conflict->id,
                'ingredient_1' => $conflict->ingredient1->ingredient_name,
                'ingredient_2' => $conflict->ingredient2->ingredient_name,
                'risk_level' => $conflict->risk_level,
                'explanation' => $conflict->explanation,
                'solution' => $conflict->solution,
                'reference_source' => $conflict->reference_source,
            ];

            switch ($conflict->risk_level) {
                case 'risky':
                    $risky[] = $formatted;
                    break;
                case 'caution':
                    $caution[] = $formatted;
                    break;
                case 'safe':
                    $safe[] = $formatted;
                    break;
                case 'recommended':
                    $recommended[] = $formatted;
                    break;
            }
        }

        $hasRisky = count($risky) > 0;
        $hasCaution = count($caution) > 0;

        return [
            'has_conflicts' => $hasRisky || $hasCaution,
            'risky_count' => count($risky),
            'caution_count' => count($caution),
            'safe_count' => count($safe),
            'recommended_count' => count($recommended),
            'risky' => $risky,
            'caution' => $caution,
            'safe' => $safe,
            'recommended' => $recommended,
            'all_conflicts' => $conflicts,
            'has_retinol_and_exfoliant' => $this->hasRetinolAndExfoliant($ingredientModels),
            'has_vitamin_c_and_retinol' => $this->hasVitCAndRetinol($ingredientModels),
            'summary' => $this->generateSummary($risky, $caution, $recommended, $safe),
        ];
    }

    /**
     * Analyze conflicts directly from a user's shelf (UserProduct collection)
     */
    public function analyzeUserProducts($userProducts): array
    {
        $allIngredients = collect();

        foreach ($userProducts as $up) {
            if ($up instanceof UserProduct) {
                if ($up->product) {
                    $allIngredients = $allIngredients->merge($up->product->ingredients);
                }
                $allIngredients = $allIngredients->merge($up->ingredients);
            }
        }

        $uniqueIngredients = $allIngredients->unique('id');
        $analysis = $this->analyzeIngredients($uniqueIngredients);
        $analysis['analyzed_products_count'] = count($userProducts);
        $analysis['unique_ingredients_count'] = $uniqueIngredients->count();

        return $analysis;
    }

    /**
     * Check if ingredients list contains both Retinol and chemical exfoliant (AHA/BHA)
     */
    public function hasRetinolAndExfoliant(Collection $ingredients): bool
    {
        $hasRetinol = $ingredients->contains(function ($item) {
            return str_contains(strtolower($item->slug), 'retinol');
        });

        $hasExfoliant = $ingredients->contains(function ($item) {
            $slug = strtolower($item->slug);
            return str_contains($slug, 'aha') || str_contains($slug, 'bha') || $item->category === 'exfoliant';
        });

        return $hasRetinol && $hasExfoliant;
    }

    /**
     * Check if ingredients list contains both Vitamin C and Retinol
     */
    public function hasVitCAndRetinol(Collection $ingredients): bool
    {
        $hasRetinol = $ingredients->contains(function ($item) {
            return str_contains(strtolower($item->slug), 'retinol');
        });

        $hasVitC = $ingredients->contains(function ($item) {
            return str_contains(strtolower($item->slug), 'vitamin-c');
        });

        return $hasRetinol && $hasVitC;
    }

    /**
     * Helper to resolve various formats into an Ingredient Collection
     */
    protected function resolveIngredientModels($ingredients): Collection
    {
        if ($ingredients instanceof Collection) {
            if ($ingredients->first() instanceof Ingredient) {
                return $ingredients;
            }
            return Ingredient::whereIn('id', $ingredients->toArray())->get();
        }

        if (is_array($ingredients)) {
            if (empty($ingredients)) {
                return collect();
            }
            if ($ingredients[0] instanceof Ingredient) {
                return collect($ingredients);
            }
            return Ingredient::whereIn('id', $ingredients)->get();
        }

        return collect();
    }

    /**
     * Generate human-readable conflict summary
     */
    protected function generateSummary(array $risky, array $caution, array $recommended, array $safe): string
    {
        if (count($risky) > 0) {
            return 'PERINGATAN: Terdeteksi ' . count($risky) . ' kombinasi bahan berisiko tinggi (RISKY) yang tidak boleh digunakan dalam satu sesi pemakaian. Disarankan menggunakan metode Skin Cycling.';
        }

        if (count($caution) > 0) {
            return 'PERHATIAN: Terdeteksi ' . count($caution) . ' kombinasi bahan aktif yang memerlukan jeda waktu atau pemisahan sesi (pagi/malam).';
        }

        if (count($recommended) > 0) {
            return 'BAGUS: Rutinitas Anda memiliki ' . count($recommended) . ' kombinasi bahan sinergis yang sangat dianjurkan oleh dermatolog.';
        }

        return 'Kombinasi bahan aktif dalam rutinitas Anda tergolong aman dan kompatibel.';
    }
}
