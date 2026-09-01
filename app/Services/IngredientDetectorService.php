<?php

namespace App\Services;

use App\Models\Ingredient;
use Illuminate\Support\Collection;

class IngredientDetectorService
{
    /**
     * Map of common INCI synonyms and trade names to ingredient slugs
     */
    protected array $synonymMap = [
        'retinol' => ['retinol', 'retinyl palmitate', 'retinal', 'retinaldehyde', 'hydroxypinacolone retinoate', 'vitamin a'],
        'niacinamide' => ['niacinamide', 'nicotinamide', 'vitamin b3', 'nicotinic acid amide'],
        'aha-glycolic-lactic-acid' => ['glycolic acid', 'lactic acid', 'mandelic acid', 'malic acid', 'tartaric acid', 'citric acid', 'aha', 'alpha hydroxy acid'],
        'bha-salicylic-acid' => ['salicylic acid', 'betaine salicylate', 'salix alba bark', 'willow bark', 'bha', 'beta hydroxy acid'],
        'vitamin-c-l-ascorbic-acid' => ['ascorbic acid', 'l-ascorbic acid', 'sodium ascorbyl phosphate', 'ethyl ascorbic acid', 'magnesium ascorbyl phosphate', 'ascorbyl glucoside', 'tetrahexyldecyl ascorbate', 'vitamin c'],
        'hyaluronic-acid' => ['hyaluronic acid', 'sodium hyaluronate', 'hydrolyzed hyaluronic acid', 'sodium acetylated hyaluronate', 'hyaluronan'],
        'ceramide' => ['ceramide np', 'ceramide ap', 'ceramide eop', 'ceramide ns', 'ceramide eos', 'ceramide 1', 'ceramide 3', 'ceramide 6', 'phytosphingosine', 'sphingolipids'],
        'benzoyl-peroxide' => ['benzoyl peroxide', 'bpo'],
        'azelaic-acid' => ['azelaic acid', 'potassium azeloyl diglycinate', 'pad'],
        'peptides' => ['copper tripeptide', 'matrixyl', 'palmitoyl pentapeptide', 'acetyl hexapeptide', 'sh-oligopeptide', 'dipeptide', 'tripeptide', 'tetrapeptide', 'hexapeptide', 'peptides'],
        'centella-asiatica-cica' => ['centella asiatica', 'madecassoside', 'asiaticoside', 'madecassic acid', 'asiatic acid', 'cica extract', 'gotu kola'],
        'sunscreen-uv-filters' => ['zinc oxide', 'titanium dioxide', 'avobenzone', 'octinoxate', 'octocrylene', 'homosalate', 'octisalate', 'tinosorb', 'uvinul', 'mexoryl', 'ethylhexyl methoxycinnamate', 'bis-ethylhexyloxyphenol methoxyphenyl triazine'],
        'bakuchiol' => ['bakuchiol', 'psoralea corylifolia'],
        'vitamin-e-tocopherol' => ['tocopherol', 'tocopheryl acetate', 'vitamin e', 'alpha-tocopherol'],
        'squalane' => ['squalane', 'squalene'],
        'panthenol-pro-vitamin-b5' => ['panthenol', 'dexpanthenol', 'pro-vitamin b5', 'pantothenic acid'],
    ];

    /**
     * Common allergens to flag
     */
    protected array $allergens = [
        'fragrance' => ['fragrance', 'parfum', 'perfume', 'linalool', 'limonene', 'geraniol', 'citronellol', 'eugenol', 'citral', 'benzyl alcohol', 'coumarin'],
        'essential_oils' => ['lavender oil', 'tea tree oil', 'eucalyptus oil', 'citrus oil', 'rosemary oil', 'peppermint oil'],
        'drying_alcohol' => ['alcohol denat', 'sd alcohol', 'denatured alcohol', 'isopropyl alcohol', 'ethanol'],
    ];

    /**
     * Parse raw free-text INCI ingredient list and detect matching master ingredients
     *
     * @param string $rawText
     * @return array
     */
    public function detectFromText(string $rawText): array
    {
        if (empty(trim($rawText))) {
            return [
                'detected_ingredients' => collect(),
                'detected_allergens' => [],
                'total_detected' => 0,
                'raw_items_count' => 0,
            ];
        }

        // Split by commas, semicolons, or newlines
        $rawItems = preg_split('/[,;\n\r]+/', $rawText);
        $cleanItems = array_values(array_filter(array_map('trim', $rawItems)));

        $detectedSlugs = [];
        $detectedAllergens = [];

        foreach ($cleanItems as $item) {
            $normalized = strtolower($item);

            // 1. Match against known active ingredient synonyms
            foreach ($this->synonymMap as $slug => $synonyms) {
                foreach ($synonyms as $synonym) {
                    if (str_contains($normalized, $synonym)) {
                        $detectedSlugs[$slug] = true;
                        break;
                    }
                }
            }

            // 2. Check for allergens (fragrance, drying alcohols, essential oils)
            foreach ($this->allergens as $allergenType => $terms) {
                foreach ($terms as $term) {
                    if (str_contains($normalized, $term)) {
                        $detectedAllergens[$allergenType][] = $item;
                        break;
                    }
                }
            }
        }

        $detectedIngredients = Ingredient::whereIn('slug', array_keys($detectedSlugs))->get();

        return [
            'detected_ingredients' => $detectedIngredients,
            'detected_slugs' => array_keys($detectedSlugs),
            'detected_allergens' => $detectedAllergens,
            'total_detected' => $detectedIngredients->count(),
            'raw_items_count' => count($cleanItems),
        ];
    }
}
