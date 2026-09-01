<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\User;

class ConsultationService
{
    protected InferenceEngineService $inferenceEngine;

    public function __construct(InferenceEngineService $inferenceEngine)
    {
        $this->inferenceEngine = $inferenceEngine;
    }

    /**
     * Process multi-step questionnaire data and create or update user consultation
     *
     * @param User $user
     * @param array $data
     * @return Consultation
     */
    public function processConsultation(User $user, array $data): Consultation
    {
        // 1. Process Modul A: Oily vs Dry (BSTI Dim 1)
        $skinType = $this->determineSkinType(
            $data['a1_sebum_condition'] ?? 'normal',
            $data['a2_pore_size'] ?? 'medium',
            $data['a3_reaction_history'] ?? []
        );

        // 2. Process Modul B: Skin Concerns (BSTI Dim 3 & 4 + Extension)
        $skinConcerns = $data['concerns'] ?? [];
        if (is_string($skinConcerns)) {
            $skinConcerns = json_decode($skinConcerns, true) ?? [$skinConcerns];
        }

        // 3. Process Modul C: Sensitive vs Resistant (BSTI Dim 2 + Extension)
        $sensitivityLevel = $this->determineSensitivityLevel(
            $data['c1_reactivity'] ?? 'resistant',
            $data['a3_reaction_history'] ?? []
        );

        $experienceLevel = $data['c2_experience_level'] ?? 'beginner';
        $retinolTolerance = $data['c3_retinol_tolerance'] ?? 'unknown';
        
        $specialConditions = $data['c4_special_conditions'] ?? [];
        if (is_string($specialConditions)) {
            $specialConditions = json_decode($specialConditions, true) ?? [];
        }

        $isPregnant = in_array('pregnant_or_nursing', $specialConditions) || !empty($data['is_pregnant']);

        // 4. Save or update Consultation record
        return Consultation::updateOrCreate(
            ['user_id' => $user->id],
            [
                'skin_type' => $skinType,
                'skin_concerns' => array_values(array_unique($skinConcerns)),
                'sensitivity_level' => $sensitivityLevel,
                'experience_level' => $experienceLevel,
                'retinol_tolerance' => $retinolTolerance,
                'is_pregnant' => $isPregnant,
                'special_conditions' => array_values(array_unique($specialConditions)),
                'raw_answers' => $data,
            ]
        );
    }

    /**
     * Determine skin type based on BSTI Module A questions (A1, A2, A3)
     */
    protected function determineSkinType(string $a1, string $a2, array $a3): string
    {
        $a1 = strtolower($a1);
        $a2 = strtolower($a2);

        if ($a1 === 'sensitive' || in_array('frequent_redness', $a3)) {
            return 'sensitive';
        }

        if ($a1 === 'oily' || ($a1 === 'combination' && $a2 === 'large')) {
            return ($a1 === 'combination') ? 'combination' : 'oily';
        }

        if ($a1 === 'dry') {
            return 'dry';
        }

        if ($a1 === 'combination') {
            return 'combination';
        }

        return 'normal';
    }

    /**
     * Determine sensitivity level based on C1 & A3
     */
    protected function determineSensitivityLevel(string $c1, array $a3): string
    {
        $c1 = strtolower($c1);

        if ($c1 === 'very_sensitive' || count($a3) >= 2) {
            return 'very_sensitive';
        }

        if ($c1 === 'sensitive' || in_array('frequent_redness', $a3) || in_array('burning_sensation', $a3)) {
            return 'sensitive';
        }

        if ($c1 === 'mildly_sensitive' || in_array('occasional_breakout', $a3)) {
            return 'mildly_sensitive';
        }

        return 'resistant';
    }
}
