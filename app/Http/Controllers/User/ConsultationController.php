<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ConsultationQuestion;
use App\Services\ConsultationService;
use App\Services\InferenceEngineService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    protected ConsultationService $consultationService;

    public function __construct(ConsultationService $consultationService)
    {
        $this->consultationService = $consultationService;
    }

    public function index()
    {
        $user = Auth::user();

        $questions = ConsultationQuestion::active()
            ->with(['activeOptions'])
            ->get();

        $existing = $user?->latestConsultation;

        return view('user.consultation.index', compact('questions', 'existing'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->only([
            'a1_sebum_condition',
            'a2_pore_size',
            'a3_reaction_history',
            'concerns',
            'c1_reactivity',
            'c2_experience_level',
            'c3_retinol_tolerance',
            'c4_special_conditions',
        ]);

        if (!$user) {
            return redirect()->route('user.consultation')
                ->with('error', 'Sesi Anda berakhir. Silakan mulai konsultasi kembali.');
        }

        $this->consultationService->processConsultation($user, $data);

        return redirect()->route('user.consultation.result')
            ->with('success', 'Konsultasi kulit Anda berhasil disimpan. Berikut hasil analisisnya.');
    }

    public function result()
    {
        $user = Auth::user();

        $consultation = $user?->latestConsultation;

        if (!$consultation) {
            return redirect()->route('user.consultation')
                ->with('info', 'Selesaikan konsultasi terlebih dahulu untuk melihat hasil analisis profil kulit Anda.');
        }

        $inference = app(InferenceEngineService::class)->infer($consultation);

        return view('user.consultation.result', [
            'consultation' => $consultation,
            'inference' => $inference,
            'skinTypeLabel' => $this->skinTypeLabel($consultation->skin_type),
            'concernLabels' => $this->concernLabels($consultation->skin_concerns),
            'sensitivityLabel' => $this->sensitivityLabel($consultation->sensitivity_level),
            'sensitivityPercent' => $this->sensitivityPercent($consultation->sensitivity_level),
            'experienceLabel' => $this->experienceLabel($consultation->experience_level),
            'retinolLabel' => $this->retinolLabel($consultation->retinol_tolerance),
            'specialConditionLabels' => $this->specialConditionLabels($consultation->special_conditions),
            'ingredientRecs' => $this->ingredientRecommendations($inference['recommendations'] ?? []),
            'strategyRecs' => $this->strategyRecommendations($inference['recommendations'] ?? []),
        ]);
    }

    protected function ingredientRecommendations(array $recommendations): array
    {
        $strategyKeys = ['zone_based', 'gel_water_based', 'skin_cycling'];

        return array_values(array_filter($recommendations, fn ($r) =>
            !in_array($r['item'], $strategyKeys, true) && !preg_match('/^[A-Za-z]+\d+$/', $r['item'])
        ));
    }

    protected function strategyRecommendations(array $recommendations): array
    {
        $strategyKeys = ['zone_based', 'gel_water_based', 'skin_cycling'];

        return array_values(array_filter($recommendations, fn ($r) =>
            in_array($r['item'], $strategyKeys, true) || preg_match('/^[A-Za-z]+\d+$/', $r['item'])
        ));
    }

    protected function skinTypeLabel(?string $type): string
    {
        return match ($type) {
            'oily' => 'Kulit Berminyak',
            'dry' => 'Kulit Kering',
            'combination' => 'Kulit Kombinasi',
            'sensitive' => 'Kulit Sensitif',
            'normal' => 'Kulit Normal',
            default => 'Profil Kulit',
        };
    }

    protected function concernLabels(?array $concerns): array
    {
        $labels = [
            'hyperpigmentation' => 'Hiperpigmentasi',
            'dullness' => 'Kusam',
            'aging' => 'Penuaan',
            'acne' => 'Jerawat',
            'dehydration' => 'Dehidrasi',
            'enlarged_pores' => 'Pori Besar',
            'sensitivity' => 'Sensitif',
            'texture' => 'Tekstur',
        ];

        $result = [];
        foreach (($concerns ?? []) as $concern) {
            $result[$concern] = $labels[$concern] ?? ucfirst(str_replace('_', ' ', $concern));
        }
        return $result;
    }

    protected function sensitivityLabel(?string $level): string
    {
        return match ($level) {
            'resistant' => 'Tahan',
            'mildly_sensitive' => 'Agak Sensitif',
            'sensitive' => 'Sensitif',
            'very_sensitive' => 'Sangat Sensitif',
            default => 'Sensitif Normal',
        };
    }

    protected function sensitivityPercent(?string $level): int
    {
        return match ($level) {
            'resistant' => 25,
            'mildly_sensitive' => 50,
            'sensitive' => 75,
            'very_sensitive' => 95,
            default => 50,
        };
    }

    protected function experienceLabel(?string $level): string
    {
        return match ($level) {
            'beginner' => 'Pemula',
            'intermediate' => 'Menengah',
            'advanced' => 'Berpengalaman',
            default => 'Pemula',
        };
    }

    protected function retinolLabel(?string $level): string
    {
        return match ($level) {
            'tolerant' => 'Toleran',
            'mild_sensitive' => 'Agak Sensitif',
            'high_sensitive' => 'Sangat Sensitif',
            'unknown' => 'Belum Pernah',
            default => 'Tidak Diketahui',
        };
    }

    protected function specialConditionLabels(?array $conditions): array
    {
        $labels = [
            'pregnant_or_nursing' => 'Hamil / Menyusui',
            'fragrance_allergy' => 'Alergi Fragrance',
            'dermatologist_treatment' => 'Dalam Perawatan Dokter Kulit',
            'none' => 'Tidak Ada Kondisi Khusus',
        ];

        $result = [];
        foreach (($conditions ?? []) as $condition) {
            $result[$condition] = $labels[$condition] ?? ucfirst(str_replace('_', ' ', $condition));
        }
        return $result;
    }
}
