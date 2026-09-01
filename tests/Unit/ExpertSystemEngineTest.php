<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\IngredientConflict;
use App\Models\Rule;
use App\Models\User;
use App\Models\UserProduct;
use App\Services\ConflictCheckerService;
use App\Services\InferenceEngineService;
use App\Services\RoutineGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpertSystemEngineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test ConflictCheckerService detects Risky, Caution, Safe, and Recommended combos
     */
    public function test_conflict_checker_identifies_risk_levels(): void
    {
        $service = app(ConflictCheckerService::class);

        $retinol = Ingredient::where('slug', 'retinol')->first();
        $aha = Ingredient::where('slug', 'aha-glycolic-lactic-acid')->first();
        $niacinamide = Ingredient::where('slug', 'niacinamide')->first();
        $ceramide = Ingredient::where('slug', 'ceramide')->first();
        $vitC = Ingredient::where('slug', 'vitamin-c-l-ascorbic-acid')->first();
        $sunscreen = Ingredient::where('slug', 'sunscreen-uv-filters')->first();

        // 1. Test Retinol + AHA (Risky)
        $analysisRisky = $service->analyzeIngredients([$retinol->id, $aha->id]);
        $this->assertTrue($analysisRisky['has_conflicts']);
        $this->assertGreaterThanOrEqual(1, $analysisRisky['risky_count']);
        $this->assertTrue($analysisRisky['has_retinol_and_exfoliant']);

        // 2. Test Niacinamide + Ceramide (Safe)
        $analysisSafe = $service->analyzeIngredients([$niacinamide->id, $ceramide->id]);
        $this->assertFalse($analysisSafe['has_conflicts']);
        $this->assertGreaterThanOrEqual(1, $analysisSafe['safe_count']);

        // 3. Test Vit C + Sunscreen (Recommended)
        $analysisRec = $service->analyzeIngredients([$vitC->id, $sunscreen->id]);
        $this->assertGreaterThanOrEqual(1, $analysisRec['recommended_count']);
    }

    /**
     * Test Forward Chaining matches facts, fires rules, and calculates Certainty Factor
     */
    public function test_inference_engine_forward_chaining_and_certainty_factor(): void
    {
        $engine = app(InferenceEngineService::class);

        // Case 1: Oily + Acne Skin
        $result = $engine->infer([
            'skin_type' => 'oily',
            'skin_concerns' => ['acne'],
            'experience_level' => 'beginner',
            'is_pregnant' => false,
        ]);

        $this->assertGreaterThan(0, $result['fired_rules_count']);
        
        $firedCodes = collect($result['fired_rules'])->pluck('rule_code')->toArray();
        $this->assertContains('R01', $firedCodes);
        $this->assertContains('R02', $firedCodes);

        $recItems = collect($result['recommendations'])->pluck('item')->toArray();
        $this->assertContains('BHA (Salicylic Acid)', $recItems);
    }

    /**
     * Test Certainty Factor combining formula: CF_comb = CF1 + CF2 * (1 - CF1)
     */
    public function test_certainty_factor_combination_formula(): void
    {
        $cf1 = 0.80;
        $cf2 = 0.60;
        // Expected: 0.80 + 0.60 * (1 - 0.80) = 0.80 + 0.12 = 0.92
        $combined = Rule::combineCF($cf1, $cf2);
        $this->assertEquals(0.92, $combined);
    }

    /**
     * Test Pregnancy safety filter blocks Retinol and issues critical alert
     */
    public function test_pregnancy_safety_filter_blocks_unsafe_actives(): void
    {
        $engine = app(InferenceEngineService::class);

        $result = $engine->infer([
            'skin_type' => 'dry',
            'skin_concerns' => ['aging', 'hyperpigmentation'],
            'is_pregnant' => true,
        ]);

        $safetyCodes = collect($result['safety_alerts'])->pluck('code')->toArray();
        $this->assertContains('SAFE_02_PREGNANCY', $safetyCodes);

        $firedCodes = collect($result['fired_rules'])->pluck('rule_code')->toArray();
        $this->assertContains('R10', $firedCodes);
    }

    /**
     * Test RoutineGenerator generates correct layering order and Skin Cycling
     */
    public function test_routine_generator_layering_and_skin_cycling(): void
    {
        $user = User::factory()->create();

        $retinol = Ingredient::where('slug', 'retinol')->first();
        $aha = Ingredient::where('slug', 'aha-glycolic-lactic-acid')->first();
        $ceramide = Ingredient::where('slug', 'ceramide')->first();
        $sunscreen = Ingredient::where('slug', 'sunscreen-uv-filters')->first();

        // Create User Products
        $cleanser = UserProduct::create([
            'user_id' => $user->id,
            'custom_name' => 'Gentle Hydrating Cleanser',
            'custom_category' => 'cleanser',
            'usage_time' => 'both',
        ]);

        $exfoliantToner = UserProduct::create([
            'user_id' => $user->id,
            'custom_name' => 'AHA Glycolic Toner',
            'custom_category' => 'exfoliating_toner',
            'usage_time' => 'night',
        ]);
        $exfoliantToner->ingredients()->attach($aha->id);

        $retinolSerum = UserProduct::create([
            'user_id' => $user->id,
            'custom_name' => 'Retinol Renewal Serum',
            'custom_category' => 'serum',
            'usage_time' => 'night',
        ]);
        $retinolSerum->ingredients()->attach($retinol->id);

        $ceramideMoisturizer = UserProduct::create([
            'user_id' => $user->id,
            'custom_name' => '5X Ceramide Barrier Cream',
            'custom_category' => 'moisturizer',
            'usage_time' => 'both',
        ]);
        $ceramideMoisturizer->ingredients()->attach($ceramide->id);

        $spf = UserProduct::create([
            'user_id' => $user->id,
            'custom_name' => 'Daily UV Shield Sunscreen SPF 50',
            'custom_category' => 'sunscreen',
            'usage_time' => 'morning',
        ]);
        $spf->ingredients()->attach($sunscreen->id);

        $generator = app(RoutineGeneratorService::class);
        $result = $generator->generateForUser($user);

        $this->assertTrue($result['success']);
        $this->assertTrue($result['needs_skin_cycling']);

        // Check Morning Routine layering
        $morningRoutine = $result['routines']['morning'];
        $morningSteps = $morningRoutine->items->pluck('category')->toArray();
        // Check Skin Cycling routines created
        $this->assertArrayHasKey('skin_cycling', $result['routines']);
        $this->assertArrayHasKey('exfoliation', $result['routines']['skin_cycling']);
        $this->assertArrayHasKey('retinoid', $result['routines']['skin_cycling']);
        $this->assertArrayHasKey('recovery', $result['routines']['skin_cycling']);
    }

    /**
     * Test IngredientDetectorService parses INCI strings and finds synonyms
     */
    public function test_ingredient_detector_parses_inci_and_allergens(): void
    {
        $detector = app(\App\Services\IngredientDetectorService::class);

        $inciText = "Aqua, Glycerin, Niacinamide 5%, Salicylic Acid, Ceramide NP, Sodium Hyaluronate, Parfum, Phenoxyethanol";
        $result = $detector->detectFromText($inciText);

        $this->assertGreaterThanOrEqual(4, $result['total_detected']);
        $this->assertContains('niacinamide', $result['detected_slugs']);
        $this->assertContains('bha-salicylic-acid', $result['detected_slugs']);
        $this->assertContains('ceramide', $result['detected_slugs']);
        $this->assertContains('hyaluronic-acid', $result['detected_slugs']);

        // Check allergen flag
        $this->assertArrayHasKey('fragrance', $result['detected_allergens']);
    }

    /**
     * Test ConsultationService processes questionnaire answers and creates Consultation model
     */
    public function test_consultation_service_processes_answers(): void
    {
        $user = User::factory()->create();
        $service = app(\App\Services\ConsultationService::class);

        $consultation = $service->processConsultation($user, [
            'a1_sebum_condition' => 'oily',
            'a2_pore_size' => 'large',
            'a3_reaction_history' => ['occasional_breakout'],
            'concerns' => ['acne', 'hyperpigmentation'],
            'c1_reactivity' => 'mildly_sensitive',
            'c2_experience_level' => 'beginner',
            'c3_retinol_tolerance' => 'unknown',
            'c4_special_conditions' => ['fragrance_allergy'],
            'is_pregnant' => false,
        ]);

        $this->assertEquals('oily', $consultation->skin_type);
        $this->assertEquals('mildly_sensitive', $consultation->sensitivity_level);
        $this->assertContains('acne', $consultation->skin_concerns);
        $this->assertContains('fragrance_allergy', $consultation->special_conditions);
    }

    /**
     * Test TrackerService manages daily checklist and streaks
     */
    public function test_tracker_service_daily_checklist_and_streak(): void
    {
        $user = User::factory()->create();
        $generator = app(\App\Services\RoutineGeneratorService::class);

        // Add a cleanser product
        $cleanser = UserProduct::create([
            'user_id' => $user->id,
            'custom_name' => 'Simple Gel Cleanser',
            'custom_category' => 'cleanser',
            'usage_time' => 'both',
        ]);

        $generator->generateForUser($user);

        $trackerService = app(\App\Services\TrackerService::class);
        $checklist = $trackerService->getDailyChecklist($user);

        $this->assertGreaterThan(0, $checklist['total_steps']);
        $this->assertEquals(0, $checklist['completed_steps']);

        // Toggle first morning step
        $firstItem = $checklist['morning_checklist'][0];
        $trackerService->toggleItemCompletion($user, $firstItem['routine_item_id']);

        $updatedChecklist = $trackerService->getDailyChecklist($user);
        $this->assertEquals(1, $updatedChecklist['completed_steps']);
    }
}
