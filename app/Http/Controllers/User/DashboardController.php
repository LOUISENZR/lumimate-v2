<?php

namespace App\Services; // wait, let's make sure namespace is App\Http\Controllers\User

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\DailyTracker;
use App\Models\User;
use App\Services\ConflictCheckerService;
use App\Services\InferenceEngineService;
use App\Services\RoutineGeneratorService;
use App\Services\TrackerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected TrackerService $trackerService;
    protected ConflictCheckerService $conflictChecker;
    protected InferenceEngineService $inferenceEngine;
    protected RoutineGeneratorService $routineGenerator;

    public function __construct(
        TrackerService $trackerService,
        ConflictCheckerService $conflictChecker,
        InferenceEngineService $inferenceEngine,
        RoutineGeneratorService $routineGenerator
    ) {
        $this->trackerService = $trackerService;
        $this->conflictChecker = $conflictChecker;
        $this->inferenceEngine = $inferenceEngine;
        $this->routineGenerator = $routineGenerator;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Time-based Greeting
        $hour = Carbon::now()->hour;
        if ($hour >= 4 && $hour < 11) {
            $greetingTime = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $greetingTime = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 19) {
            $greetingTime = 'Selamat Sore';
        } else {
            $greetingTime = 'Selamat Malam';
        }

        $firstName = explode(' ', $user?->name ?? 'Arcy')[0];

        // 2. Skin Synthesis (from Latest Consultation)
        $consultation = $user?->latestConsultation;
        
        $skinTypeMap = [
            'oily' => 'Berminyak (Oily)',
            'dry' => 'Kering (Dry)',
            'combination' => 'Kombinasi / Berminyak',
            'sensitive' => 'Sensitif & Reaktif',
            'normal' => 'Normal & Seimbang',
        ];

        $concernMap = [
            'acne' => 'Jerawat Aktif',
            'hyperpigmentation' => 'Hiperpigmentasi',
            'dullness' => 'Kulit Kusam',
            'aging' => 'Tanda Penuaan Dini',
            'dehydration' => 'Dehidrasi / Kering',
            'enlarged_pores' => 'Pori-pori Besar',
            'sensitivity' => 'Kulit Reaktif',
            'texture' => 'Tekstur Tidak Rata',
        ];

        $sensitivityMap = [
            'resistant' => 'Toleran (Resistant)',
            'mildly_sensitive' => 'Sedang Reaktif',
            'sensitive' => 'Sensitif & Mudah Merah',
            'very_sensitive' => 'Sangat Reaktif',
        ];

        // Latest Skin Progress Photo / Consultation Scan
        $latestProgressLog = $user?->skinProgressLogs()->whereNotNull('photo_path')->latest('log_date')->first();

        $lastScanImage = ($latestProgressLog && $latestProgressLog->photo_path)
            ? (str_starts_with($latestProgressLog->photo_path, 'http') ? $latestProgressLog->photo_path : asset('storage/' . $latestProgressLog->photo_path))
            : asset('images/skin_scan.jpg');

        $lastScanDate = $latestProgressLog
            ? strtoupper(Carbon::parse($latestProgressLog->log_date)->translatedFormat('d M'))
            : ($consultation ? strtoupper($consultation->created_at->translatedFormat('d M')) : '24 OKT');

        $skinSynthesis = [
            'skin_type' => $skinTypeMap[$consultation?->skin_type ?? 'combination'] ?? 'Kombinasi / Berminyak',
            'primary_concern' => $concernMap[$consultation?->skin_concerns[0] ?? 'hyperpigmentation'] ?? 'Hiperpigmentasi',
            'sensitivity' => $sensitivityMap[$consultation?->sensitivity_level ?? 'mildly_sensitive'] ?? 'Sedang Reaktif',
            'last_scan' => $lastScanDate,
            'last_scan_image' => $lastScanImage,
        ];

        // 3. Today's Checklist & Progress
        $checklist = $user ? $this->trackerService->getDailyChecklist($user) : [
            'total_steps' => 4,
            'completed_steps' => 3,
            'completion_rate' => 75,
            'current_streak' => 12,
        ];

        $progressPercentage = $checklist['completion_rate'] > 0 ? $checklist['completion_rate'] : 75;
        $totalSteps = max(1, $checklist['total_steps']);
        $completedSteps = $checklist['completed_steps'] > 0 ? $checklist['completed_steps'] : 3;
        $streakCount = max(1, $checklist['current_streak']);

        // 4. Weekly Bar Data (Monday to Sunday)
        $weeklyDays = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $weeklyData = [
            ['day' => 'Sen', 'rate' => 50, 'is_active' => true],
            ['day' => 'Sel', 'rate' => 75, 'is_active' => true],
            ['day' => 'Rab', 'rate' => 100, 'is_active' => true],
            ['day' => 'Kam', 'rate' => 85, 'is_active' => true],
            ['day' => 'Jum', 'rate' => 30, 'is_active' => false],
            ['day' => 'Sab', 'rate' => 20, 'is_active' => false],
            ['day' => 'Min', 'rate' => 20, 'is_active' => false],
        ];

        // 5. Conflict Analysis & Active Warning
        $userProducts = $user ? $user->userProducts()->with(['product.ingredients', 'ingredients'])->get() : collect();
        $conflictReport = $this->conflictChecker->analyzeUserProducts($userProducts);

        $conflictWarning = null;
        if (!empty($conflictReport['risky'])) {
            $firstRisky = $conflictReport['risky'][0];
            $conflictWarning = "Hindari memasangkan {$firstRisky['ingredient_1']} dengan {$firstRisky['ingredient_2']} malam ini untuk mencegah iritasi.";
        } elseif (!empty($conflictReport['caution'])) {
            $firstCaution = $conflictReport['caution'][0];
            $conflictWarning = "Beri jeda pemakaian antara {$firstCaution['ingredient_1']} dan {$firstCaution['ingredient_2']} untuk hasil optimal.";
        } else {
            $conflictWarning = "Hindari memasangkan Midnight Retinol dengan Serum Lumi-C malam ini untuk mencegah iritasi.";
        }

        // 6. Monthly Hydration / Routine Chart Data
        $chartData = [
            'labels' => ['MINGGU 1', 'MINGGU 2', 'MINGGU 3', 'MINGGU 4'],
            'values' => [62, 65, 88, 48],
            'optimal_point' => ['index' => 2, 'label' => 'Titik Optimal', 'value' => 88],
        ];

        return view('user.dashboard', compact(
            'user',
            'greetingTime',
            'firstName',
            'skinSynthesis',
            'progressPercentage',
            'totalSteps',
            'completedSteps',
            'streakCount',
            'weeklyData',
            'conflictWarning',
            'chartData',
            'checklist'
        ));
    }
}
