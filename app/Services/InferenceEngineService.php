<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\Ingredient;
use App\Models\Rule;
use App\Models\User;
use Illuminate\Support\Collection;

class InferenceEngineService
{
    /**
     * Run the Forward Chaining Expert System on a Consultation model or facts array
     *
     * @param Consultation|array $input
     * @param Collection|array|null $userProducts
     * @return array
     */
    public function infer($input, $userProducts = null): array
    {
        // 1. Build Working Memory (Facts)
        $facts = $this->buildFacts($input, $userProducts);

        // 2. Fetch all active expert rules
        $rules = Rule::active()->get();

        $firedRules = [];
        $rawRecommendations = [];
        $frequencyGuidelines = [];
        $safetyAlerts = [];

        // 3. Inference Loop (Forward Chaining Pattern Matching)
        foreach ($rules as $rule) {
            if ($rule->matches($facts)) {
                $firedRules[] = [
                    'rule_code' => $rule->rule_code,
                    'rule_type' => $rule->rule_type,
                    'actions' => $rule->actions,
                    'certainty_factor' => (float) $rule->certainty_factor,
                    'explanation' => $rule->explanation,
                    'reference_source' => $rule->reference_source,
                ];

                // Process Recommendations
                if ($rule->rule_type === 'recommendation') {
                    $key = $rule->actions['recommend_ingredient'] ?? ($rule->actions['recommend_strategy'] ?? $rule->rule_code);
                    if (!isset($rawRecommendations[$key])) {
                        $rawRecommendations[$key] = [
                            'item' => $key,
                            'message' => $rule->actions['message'] ?? '',
                            'cf' => (float) $rule->certainty_factor,
                            'explanations' => [$rule->explanation],
                            'references' => [$rule->reference_source],
                            'fired_by' => [$rule->rule_code],
                        ];
                    } else {
                        // Combine Certainty Factors: CF_combined = CF1 + CF2 * (1 - CF1)
                        $oldCF = $rawRecommendations[$key]['cf'];
                        $newCF = (float) $rule->certainty_factor;
                        $rawRecommendations[$key]['cf'] = Rule::combineCF($oldCF, $newCF);
                        $rawRecommendations[$key]['explanations'][] = $rule->explanation;
                        $rawRecommendations[$key]['references'][] = $rule->reference_source;
                        $rawRecommendations[$key]['fired_by'][] = $rule->rule_code;
                    }
                }

                // Process Frequency Guidelines
                if ($rule->rule_type === 'frequency') {
                    $ingredientName = $rule->conditions['ingredient'] ?? 'General';
                    $frequencyGuidelines[$ingredientName] = array_merge(
                        $rule->actions,
                        [
                            'cf' => (float) $rule->certainty_factor,
                            'explanation' => $rule->explanation,
                            'reference' => $rule->reference_source,
                        ]
                    );
                }
            }
        }

        // 4. Special Mandatory Safety & Boundary Handlers
        $safetyAlerts = $this->evaluateSafetyScenarios($facts);

        // Sort recommendations by combined CF descending
        uasort($rawRecommendations, function ($a, $b) {
            return $b['cf'] <=> $a['cf'];
        });

        return [
            'facts' => $facts,
            'fired_rules_count' => count($firedRules),
            'fired_rules' => $firedRules,
            'recommendations' => array_values($rawRecommendations),
            'frequency_guidelines' => $frequencyGuidelines,
            'safety_alerts' => $safetyAlerts,
            'skin_type_summary' => $this->describeSkinType($facts['skin_type']),
        ];
    }

    /**
     * Build facts array for working memory
     */
    public function buildFacts($input, $userProducts = null): array
    {
        if ($input instanceof Consultation) {
            $facts = [
                'skin_type' => $input->skin_type,
                'skin_concerns' => $input->skin_concerns ?? [],
                'sensitivity_level' => $input->sensitivity_level ?? 'resistant',
                'experience_level' => $input->experience_level ?? 'beginner',
                'retinol_tolerance' => $input->retinol_tolerance ?? 'unknown',
                'is_pregnant' => (bool) $input->is_pregnant,
                'special_conditions' => $input->special_conditions ?? [],
            ];
        } elseif (is_array($input)) {
            $facts = array_merge([
                'skin_type' => 'normal',
                'skin_concerns' => [],
                'sensitivity_level' => 'resistant',
                'experience_level' => 'beginner',
                'retinol_tolerance' => 'unknown',
                'is_pregnant' => false,
                'special_conditions' => [],
            ], $input);
        } else {
            $facts = [];
        }

        // Process products & ingredients if provided
        $detectedIngredients = [];
        $hasSunscreen = false;
        $activeCount = 0;

        if ($userProducts) {
            foreach ($userProducts as $up) {
                $category = $up->display_category ?? ($up->category ?? '');
                if (strtolower($category) === 'sunscreen') {
                    $hasSunscreen = true;
                }

                // Check ingredients
                $ingredients = $up->product ? $up->product->ingredients : $up->ingredients;
                if ($ingredients) {
                    foreach ($ingredients as $ing) {
                        $name = $ing->ingredient_name;
                        $slug = $ing->slug;
                        $detectedIngredients[] = $name;
                        $detectedIngredients[] = $slug;

                        if (in_array($ing->category, ['actives', 'exfoliant'])) {
                            $activeCount++;
                        }
                    }
                }
            }
        }

        $facts['ingredients'] = array_unique($detectedIngredients);
        $facts['has_sunscreen'] = $hasSunscreen;
        $facts['strong_actives_count'] = $activeCount;

        return $facts;
    }

    /**
     * Evaluate mandatory safety scenarios from Section 7.2 of planning doc
     */
    protected function evaluateSafetyScenarios(array $facts): array
    {
        $alerts = [];

        // Scenario 1: Missing Sunscreen Warning
        if (isset($facts['has_sunscreen']) && $facts['has_sunscreen'] === false) {
            $alerts[] = [
                'code' => 'SAFE_01_NO_SUNSCREEN',
                'level' => 'critical',
                'title' => 'Peringatan Wajib: Sunscreen Belum Ditambahkan',
                'message' => 'Sunscreen adalah langkah WAJIB di pagi hari, terutama jika rutinitas Anda melibatkan bahan aktif (AHA, BHA, Retinol, atau Vitamin C). Kulit yang terekspos UV tanpa proteksi akan memperparah hiperpigmentasi dan mempercepat penuaan sel.',
                'action_required' => 'Tambahkan produk sunscreen minimal SPF 30 untuk mengaktifkan morning routine.',
            ];
        }

        // Scenario 2: Pregnancy Filter
        if (!empty($facts['is_pregnant'])) {
            $alerts[] = [
                'code' => 'SAFE_02_PREGNANCY',
                'level' => 'critical',
                'title' => 'Protokol Keamanan Kehamilan & Menyusui Aktif',
                'message' => 'Sistem secara otomatis memblokir Retinol, derivatif Vitamin A, dan Salicylic Acid (BHA) dosis tinggi dari daftar rekomendasi. Sebagai alternatif aman, sistem merekomendasikan Azelaic Acid, Bakuchiol, dan Vitamin C.',
                'disclaimer' => 'Selalu konsultasikan penambahan produk baru dengan dokter kandungan Anda sebelum memulai rutinitas.',
            ];
        }

        // Scenario 3: Beginner with too many active products
        if (($facts['experience_level'] ?? '') === 'beginner' && ($facts['strong_actives_count'] ?? 0) > 2) {
            $alerts[] = [
                'code' => 'SAFE_03_BEGINNER_SIMPLIFICATION',
                'level' => 'warning',
                'title' => 'Saran Penyederhanaan Rutinitas (Beginner)',
                'message' => 'Anda terdeteksi memiliki lebih dari 2 bahan aktif kuat dalam rutinitas pemula. Untuk mencegah over-exfoliation dan kerusakan skin barrier, disarankan menguasai 1 bahan aktif terlebih dahulu selama 4 minggu sebelum menambah bahan aktif berikutnya.',
            ];
        }

        // Scenario 4: Fragrance Allergy
        $specialConditions = $facts['special_conditions'] ?? [];
        if (in_array('fragrance_allergy', $specialConditions)) {
            $alerts[] = [
                'code' => 'SAFE_04_FRAGRANCE_ALLERGY',
                'level' => 'warning',
                'title' => 'Peringatan Riwayat Alergi Fragrance',
                'message' => 'Hindari produk yang mencantumkan "fragrance", "parfum", atau "essential oils" pada label kemasannya untuk mencegah dermatitis kontak.',
            ];
        }

        // Scenario 5: Under Dermatologist Treatment
        if (in_array('dermatologist_treatment', $specialConditions)) {
            $alerts[] = [
                'code' => 'SAFE_05_DERMATOLOGIST_TREATMENT',
                'level' => 'info',
                'title' => 'Dalam Perawatan Dokter Kulit',
                'message' => 'Pastikan untuk mengonsultasikan setiap produk baru dengan dokter kulit Anda agar tidak berinterferensi dengan obat resep yang sedang berjalan.',
            ];
        }

        return $alerts;
    }

    /**
     * Provide medical explanation for skin type
     */
    protected function describeSkinType(?string $skinType): string
    {
        return match ($skinType) {
            'oily' => 'Kulit Berminyak (Oily): Aktivitas kelenjar sebaceous tinggi di seluruh zona wajah. Membutuhkan kontrol sebum, pembersihan pori dengan BHA, dan hidrasi ringan berbahan dasar air.',
            'dry' => 'Kulit Kering (Dry): Produksi lipid alami dan sebum rendah, rentan kehilangan kelembapan (TEWL). Membutuhkan humektan kaya (Hyaluronic Acid) dan lipid pengunci (Ceramide).',
            'combination' => 'Kulit Kombinasi: Produksi sebum tinggi di area T-zone (dahi, hidung, dagu) dan normal/kering di area U-zone (pipi). Membutuhkan pendekatan berbasis zona (zone-based approach).',
            'sensitive' => 'Kulit Sensitif: Skin barrier memiliki reaktivitas tinggi dan mudah mengalami eritema/perih. Membutuhkan bahan anti-inflamasi (Centella Asiatica, Azelaic Acid) serta menghindari asam kuat.',
            'normal' => 'Kulit Normal: Keseimbangan optimal antara produksi lipid dan kadar hidrasi. Fokus pada proteksi antioksidan pagi dan perawatan barrier malam.',
            default => 'Kondisi kulit teridentifikasi.',
        };
    }
}
