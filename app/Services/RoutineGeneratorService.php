<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\RoutineItem;
use App\Models\SkincareRoutine;
use App\Models\User;
use App\Models\UserProduct;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RoutineGeneratorService
{
    /**
     * Category order ranking (from thinnest to thickest consistency)
     */
    protected array $layeringOrder = [
        'cleanser' => 1,
        'hydrating_toner' => 2,
        'exfoliating_toner' => 3,
        'serum' => 4,
        'spot_treatment' => 5,
        'eye_cream' => 6,
        'moisturizer' => 7,
        'face_oil' => 8,
        'sunscreen' => 9,
        'other' => 10,
    ];

    protected ConflictCheckerService $conflictChecker;

    public function __construct(ConflictCheckerService $conflictChecker)
    {
        $this->conflictChecker = $conflictChecker;
    }

    /**
     * Generate structured routines for a user and save to database
     *
     * @param User $user
     * @param Consultation|null $consultation
     * @return array
     */
    public function generateForUser(User $user, ?Consultation $consultation = null): array
    {
        $userProducts = $user->userProducts()->with(['product.ingredients', 'ingredients'])->where('is_active', true)->get();
        
        if ($userProducts->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Belum ada produk skincare di rak pengguna.',
                'routines' => [],
            ];
        }

        $conflictAnalysis = $this->conflictChecker->analyzeUserProducts($userProducts);
        $needsSkinCycling = $conflictAnalysis['has_retinol_and_exfoliant'] || $conflictAnalysis['risky_count'] > 0;

        return DB::transaction(function () use ($user, $consultation, $userProducts, $needsSkinCycling, $conflictAnalysis) {
            // Delete old generated routines
            SkincareRoutine::where('user_id', $user->id)->delete();

            $createdRoutines = [];

            // 1. Generate Morning (AM) Routine
            $amRoutine = $this->createMorningRoutine($user, $consultation, $userProducts);
            $createdRoutines['morning'] = $amRoutine;

            // 2. Generate Night (PM) Routine or Skin Cycling Schedule
            if ($needsSkinCycling) {
                $cyclingRoutines = $this->createSkinCyclingRoutines($user, $consultation, $userProducts);
                $createdRoutines['skin_cycling'] = $cyclingRoutines;
            } else {
                $pmRoutine = $this->createStandardNightRoutine($user, $consultation, $userProducts);
                $createdRoutines['night'] = $pmRoutine;
            }

            return [
                'success' => true,
                'needs_skin_cycling' => $needsSkinCycling,
                'conflict_analysis' => $conflictAnalysis,
                'routines' => $createdRoutines,
            ];
        });
    }

    /**
     * Build Morning (AM) Routine
     */
    protected function createMorningRoutine(User $user, ?Consultation $consultation, Collection $products): SkincareRoutine
    {
        $routine = SkincareRoutine::create([
            'user_id' => $user->id,
            'consultation_id' => $consultation?->id,
            'routine_type' => 'morning',
            'day_of_week' => 'all',
            'cycling_phase' => 'daily',
            'notes' => 'Rutinitas Pagi: Fokus pada pembersihan gentle, hidrasi, antioksidan, dan proteksi maksimal Sunscreen SPF.',
        ]);

        // Filter products suitable for morning (exclude Retinol and night-only exfoliants)
        $amProducts = $products->filter(function ($up) {
            $usageTime = $up->usage_time ?? 'both';
            if ($usageTime === 'night') {
                return false;
            }

            // Never include Retinol in AM
            $hasRetinol = $this->productHasIngredient($up, 'retinol');
            return !$hasRetinol;
        });

        // Sort by layering order
        $sorted = $this->sortByLayeringOrder($amProducts);

        $step = 1;
        foreach ($sorted as $up) {
            $category = $this->normalizeCategory($up->display_category);
            RoutineItem::create([
                'routine_id' => $routine->id,
                'user_product_id' => $up->id,
                'step_order' => $step++,
                'category' => $category,
                'usage_instructions' => $this->getUsageInstruction($category, 'morning'),
            ]);
        }

        return $routine->load('items.userProduct.product');
    }

    /**
     * Build Standard Night (PM) Routine
     */
    protected function createStandardNightRoutine(User $user, ?Consultation $consultation, Collection $products): SkincareRoutine
    {
        $routine = SkincareRoutine::create([
            'user_id' => $user->id,
            'consultation_id' => $consultation?->id,
            'routine_type' => 'night',
            'day_of_week' => 'all',
            'cycling_phase' => 'daily',
            'notes' => 'Rutinitas Malam: Fokus pada regenerasi sel dan penguatan skin barrier saat istirahat tidur.',
        ]);

        // Filter products suitable for night (exclude Sunscreen)
        $pmProducts = $products->filter(function ($up) {
            $usageTime = $up->usage_time ?? 'both';
            if ($usageTime === 'morning') {
                return false;
            }

            $category = $this->normalizeCategory($up->display_category);
            return $category !== 'sunscreen';
        });

        $sorted = $this->sortByLayeringOrder($pmProducts);

        $step = 1;
        foreach ($sorted as $up) {
            $category = $this->normalizeCategory($up->display_category);
            RoutineItem::create([
                'routine_id' => $routine->id,
                'user_product_id' => $up->id,
                'step_order' => $step++,
                'category' => $category,
                'usage_instructions' => $this->getUsageInstruction($category, 'night'),
            ]);
        }

        return $routine->load('items.userProduct.product');
    }

    /**
     * Build 4-Phase Skin Cycling Routines for PM
     */
    protected function createSkinCyclingRoutines(User $user, ?Consultation $consultation, Collection $products): array
    {
        $pmProducts = $products->filter(function ($up) {
            $category = $this->normalizeCategory($up->display_category);
            return $category !== 'sunscreen' && ($up->usage_time ?? 'both') !== 'morning';
        });

        // 1. Exfoliation Night (Malam 1: Senin & Kamis)
        $exfoliationRoutine = SkincareRoutine::create([
            'user_id' => $user->id,
            'consultation_id' => $consultation?->id,
            'routine_type' => 'skin_cycling',
            'day_of_week' => 'monday', // Also applies to Thursday
            'cycling_phase' => 'exfoliation',
            'notes' => 'Malam 1 (Exfoliation Night): Eksfoliasi sel kulit mati dengan AHA/BHA untuk membersihkan pori. (Jadwal: Senin & Kamis)',
        ]);

        $exfoliationProducts = $pmProducts->filter(fn($up) => !$this->productHasIngredient($up, 'retinol'));
        $this->attachRoutineItems($exfoliationRoutine, $this->sortByLayeringOrder($exfoliationProducts), 'exfoliation');

        // 2. Retinoid Night (Malam 2: Selasa & Jumat)
        $retinoidRoutine = SkincareRoutine::create([
            'user_id' => $user->id,
            'consultation_id' => $consultation?->id,
            'routine_type' => 'skin_cycling',
            'day_of_week' => 'tuesday', // Also applies to Friday
            'cycling_phase' => 'retinoid',
            'notes' => 'Malam 2 (Retinoid Night): Stimulasi regenerasi sel dan kolagen dengan Retinol. (Jadwal: Selasa & Jumat)',
        ]);

        $retinoidProducts = $pmProducts->filter(function ($up) {
            $category = $this->normalizeCategory($up->display_category);
            return $category !== 'exfoliating_toner' && !$this->productHasIngredient($up, 'aha') && !$this->productHasIngredient($up, 'bha');
        });
        $this->attachRoutineItems($retinoidRoutine, $this->sortByLayeringOrder($retinoidProducts), 'retinoid');

        // 3. Recovery Nights (Malam 3 & 4: Rabu, Sabtu, Minggu)
        $recoveryRoutine = SkincareRoutine::create([
            'user_id' => $user->id,
            'consultation_id' => $consultation?->id,
            'routine_type' => 'skin_cycling',
            'day_of_week' => 'wednesday',
            'cycling_phase' => 'recovery',
            'notes' => 'Malam 3 & 4 (Recovery Night): Istirahat dari bahan aktif kuat, fokus pada hidrasi mendalam dan pemulihan Ceramide barrier. (Jadwal: Rabu, Sabtu, Minggu)',
        ]);

        $recoveryProducts = $pmProducts->filter(function ($up) {
            $hasRetinol = $this->productHasIngredient($up, 'retinol');
            $hasAHA = $this->productHasIngredient($up, 'aha');
            $hasBHA = $this->productHasIngredient($up, 'bha');
            $hasBPO = $this->productHasIngredient($up, 'benzoyl-peroxide');
            $category = $this->normalizeCategory($up->display_category);

            return !$hasRetinol && !$hasAHA && !$hasBHA && !$hasBPO && $category !== 'exfoliating_toner';
        });
        $this->attachRoutineItems($recoveryRoutine, $this->sortByLayeringOrder($recoveryProducts), 'recovery');

        return [
            'exfoliation' => $exfoliationRoutine->load('items.userProduct.product'),
            'retinoid' => $retinoidRoutine->load('items.userProduct.product'),
            'recovery' => $recoveryRoutine->load('items.userProduct.product'),
        ];
    }

    /**
     * Helper to sort user products by layering hierarchy
     */
    protected function sortByLayeringOrder(Collection $products): Collection
    {
        return $products->sortBy(function ($up) {
            $cat = $this->normalizeCategory($up->display_category);
            return $this->layeringOrder[$cat] ?? 99;
        })->values();
    }

    /**
     * Helper to attach routine items
     */
    protected function attachRoutineItems(SkincareRoutine $routine, Collection $sortedProducts, string $phase): void
    {
        $step = 1;
        foreach ($sortedProducts as $up) {
            $category = $this->normalizeCategory($up->display_category);
            RoutineItem::create([
                'routine_id' => $routine->id,
                'user_product_id' => $up->id,
                'step_order' => $step++,
                'category' => $category,
                'usage_instructions' => $this->getUsageInstruction($category, $phase),
            ]);
        }
    }

    /**
     * Check if product contains a specific ingredient slug
     */
    protected function productHasIngredient(UserProduct $up, string $keyword): bool
    {
        $ingredients = $up->product ? $up->product->ingredients : $up->ingredients;
        if (!$ingredients) {
            return false;
        }

        return $ingredients->contains(function ($item) use ($keyword) {
            return str_contains(strtolower($item->slug), strtolower($keyword));
        });
    }

    /**
     * Normalize category string
     */
    protected function normalizeCategory(?string $cat): string
    {
        if (!$cat) return 'serum';
        $cat = strtolower(str_replace([' ', '-'], '_', trim($cat)));
        return array_key_exists($cat, $this->layeringOrder) ? $cat : 'serum';
    }

    /**
     * Generate step-specific usage instruction
     */
    protected function getUsageInstruction(string $category, string $context): string
    {
        return match ($category) {
            'cleanser' => 'Basahi wajah dengan air hangat kuku, tuang cleanser secukupnya, pijat lembut gerakan memutar 60 detik, lalu bilas bersih.',
            'hydrating_toner' => 'Tuang 3-4 tetes ke telapak tangan atau kapas, tepuk-tepuk lembut ke seluruh wajah hingga terasa lembap.',
            'exfoliating_toner' => 'Gunakan kapas, usap lembut ke area wajah menghindari area mata dan bibir. Tunggu 1-2 menit sebelum langkah berikutnya.',
            'serum' => 'Teteskan 2-3 tetes ke wajah, ratakan ke dahi dan pipi, tekan lembut dengan ujung jari hingga meresap sempurna.',
            'spot_treatment' => 'Totolkan sedikit langsung pada mata jerawat aktif atau area bekas jerawat setelah serum meresap.',
            'eye_cream' => 'Gunakan jari manis, tepuk ringan di sekitar tulang orbita bawah mata dari arah dalam ke luar.',
            'moisturizer' => 'Ambil seukuran koin, ratakan ke seluruh wajah dan leher untuk mengunci hidrasi dan memperkuat skin barrier.',
            'face_oil' => 'Hangatkan 1-2 tetes minyak di telapak tangan, tekan lembut sebagai lapisan penutup di malam hari.',
            'sunscreen' => 'LANGKAH TERAKHIR WAJIB: Aplikasikan sebanyak 2 jari (two-finger rule) secara merata ke seluruh wajah dan leher 15 menit sebelum terpapar matahari.',
            default => 'Aplikasikan secara merata ke seluruh permukaan kulit wajah yang bersih.',
        };
    }
}
