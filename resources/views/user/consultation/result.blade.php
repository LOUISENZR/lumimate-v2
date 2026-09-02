@extends('layouts.app')

@section('title', 'Hasil Konsultasi — LumiMate')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-garamond { font-family: 'EB Garamond', Georgia, serif; }
        .font-manrope { font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif; }
        .result-card {
            background: #FFFFFF;
            border: 1px solid rgba(218, 193, 191, 0.35);
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(77, 14, 18, 0.05);
        }
        .section-number {
            font-family: 'EB Garamond', Georgia, serif;
            font-size: 13px;
            letter-spacing: 0.4em;
            color: #DAC1BF;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: #FFFFFF !important; }
            .result-card { box-shadow: none; border-color: #E5E2DD; }
        }
    </style>
@endpush

@section('content')
    @php
        $facts = $inference['facts'] ?? [];
        $safetyAlerts = $inference['safety_alerts'] ?? [];
        $frequencyGuidelines = $inference['frequency_guidelines'] ?? [];
        $firedRules = $inference['fired_rules'] ?? [];

        $focusMap = [
            'oily' => 'Kontrol Sebum & Pembersihan Pori',
            'dry' => 'Restorasi Lipida & Hidrasi',
            'combination' => 'Pendekatan Berbasis Zona (T-Zone / U-Zone)',
            'sensitive' => 'Rehabilitasi & Penguatan Skin Barrier',
            'normal' => 'Pertahanan Antioksidan & Keseimbangan',
        ];
        $strategicFocus = $focusMap[$consultation->skin_type] ?? 'Keseimbangan Rutinitas';

        $restrictItems = [];
        if (!empty($consultation->is_pregnant)) {
            $restrictItems[] = ['name' => 'Retinol & Derivatif Vitamin A', 'detail' => 'Diblokir oleh protokol keamanan kehamilan (muai, menyusui).'];
            $restrictItems[] = ['name' => 'Salicylic Acid (BHA) Dosis Tinggi', 'detail' => 'Diblokir oleh protokol keamanan kehamilan.'];
        }
        if (in_array('fragrance_allergy', $consultation->special_conditions ?? [])) {
            $restrictItems[] = ['name' => 'Fragrance, Parfum & Essential Oils', 'detail' => 'Riwayat alergi fragrance terdeteksi pada profil Anda.'];
        }
        if (in_array('dermatologist_treatment', $consultation->special_conditions ?? [])) {
            $restrictItems[] = ['name' => 'Bahan Aktif Baru Tanpa Persetujuan Dokter', 'detail' => 'Anda sedang dalam perawatan dokter kulit.'];
        }
        if (($consultation->retinol_tolerance ?? '') === 'high_sensitive') {
            $restrictItems[] = ['name' => 'Retinol Konsentrasi Tinggi', 'detail' => 'Toleransi retinol rendah; mulai dari dosis terendah atau Bakuchiol.'];
        }
        if (in_array($consultation->sensitivity_level, ['sensitive', 'very_sensitive'])) {
            $restrictItems[] = ['name' => 'Bahan dengan Tingkat Iritasi Tinggi', 'detail' => 'Kulit teridentifikasi sensitif / sangat reaktif.'];
        }
        if (empty($restrictItems)) {
            $restrictItems[] = ['name' => 'Tidak Ada Pantangan Khusus', 'detail' => 'Profil Anda tidak menunjukkan kondisi yang membatasi pemilihan bahan.'];
        }
    @endphp

    <div class="max-w-[1024px] mx-auto font-manrope text-[#2D0003]">

        <!-- Header -->
        <header class="pb-12 border-b border-[#E5E2DD]">
            <div class="flex items-center gap-3 mb-6">
                <span class="section-number">HASIL ANALISIS</span>
                <span class="h-px w-10 bg-[#DAC1BF]"></span>
                <span class="text-xs font-medium uppercase tracking-[0.075em] text-[#544242]">{{ $consultation->created_at->format('d M Y') }}</span>
            </div>
            <h1 class="font-garamond text-4xl sm:text-[56px] leading-[1.05] font-medium text-[#2D0003] tracking-[-0.01em]">
                Hasil Analisis Profil
            </h1>
            <p class="mt-5 text-lg leading-relaxed text-[#544242] max-w-[560px]">
                Rekomendasi personalisasi berdasarkan evaluasi dermatologis digital Anda.
            </p>
        </header>

        <!-- Section 1: Profil Utama -->
        <section class="pt-12">
            <div class="flex items-center gap-3 mb-6">
                <span class="section-number">01</span>
                <span class="h-px flex-1 bg-[#E5E2DD]"></span>
                <span class="text-xs font-medium uppercase tracking-[0.075em] text-[#544242]">Profil Utama</span>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Primary Skin Type -->
                <article class="result-card p-8 lg:col-span-1">
                    <span class="text-xs font-semibold uppercase tracking-[0.1em] text-[#544242]">Tipe Kulit Utama</span>
                    <h2 class="font-garamond text-4xl sm:text-[56px] leading-[1.05] font-medium text-[#2D0003] mt-4">
                        {{ $skinTypeLabel }}
                    </h2>
                    <p class="mt-5 text-[15px] leading-relaxed text-[#544242]">
                        {{ $inference['skin_type_summary'] ?? '' }}
                    </p>
                </article>

                <!-- Target Concerns -->
                <article class="result-card p-8 flex flex-col">
                    <span class="text-xs font-semibold uppercase tracking-[0.1em] text-[#544242]">Target Kekhawatiran</span>
                    <div class="mt-5 flex flex-wrap gap-2.5">
                        @forelse ($concernLabels as $key => $label)
                            <span class="px-4 py-2 rounded-full text-xs font-semibold text-[#765C5D] bg-[#FAD7D8]/20 border border-[#FAD7D8]/50">
                                {{ $label }}
                            </span>
                        @empty
                            <span class="text-sm text-[#544242]">Tidak ada masalah kulit utama yang dipilih.</span>
                        @endforelse
                    </div>

                    <div class="mt-auto pt-6">
                        <span class="text-xs font-semibold uppercase tracking-[0.1em] text-[#544242]">Kondisi Khusus</span>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @forelse ($specialConditionLabels as $condition => $label)
                                <span class="text-xs text-[#544242]">{{ $label }}</span>
                            @empty
                                <span class="text-xs text-[#544242]">Tidak ada</span>
                            @endforelse
                        </div>
                    </div>
                </article>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 mt-6">
                <!-- Experience -->
                <article class="result-card p-8 flex items-end justify-between gap-6">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-[0.1em] text-[#544242]">Experience</span>
                        <p class="font-garamond text-5xl font-medium text-[#2D0003] mt-4">{{ $experienceLabel }}</p>
                        <p class="mt-2 text-sm text-[#544242]">Lamanya pemakaian bahan aktif rutin.</p>
                    </div>
                    <span class="shrink-0 rounded-full w-16 h-16 flex items-center justify-center bg-[#2D0003] text-white text-xl font-semibold">
                        {{ $consultation->experience_level === 'advanced' ? '3' : ($consultation->experience_level === 'intermediate' ? '2' : '1') }}
                    </span>
                </article>

                <!-- Sensitivity -->
                <article class="result-card p-8">
                    <div class="flex items-end justify-between gap-6">
                        <span class="text-xs font-semibold uppercase tracking-[0.1em] text-[#544242]">Sensitivitas</span>
                        <span class="text-sm font-semibold text-[#2D0003]">{{ $sensitivityLabel }}</span>
                    </div>
                    <p class="font-garamond text-5xl font-medium text-[#2D0003] mt-4">
                        {{ $sensitivityPercent }}<span class="text-2xl text-[#544242]">%</span>
                    </p>
                    <div class="mt-5 h-1 w-full rounded-full bg-[#E5E2DD] overflow-hidden">
                        <div class="h-full rounded-full bg-[#2D0003] transition-all duration-700" style="width: {{ $sensitivityPercent }}%"></div>
                    </div>
                    <p class="mt-3 text-sm text-[#544242]">Tingkat reaktivitas &amp; toleransi kulit terhadap bahan aktif.</p>
                </article>
            </div>
        </section>

        <!-- Section 2: Botanical Selection -->
        <section class="pt-14">
            <div class="flex items-center gap-3 mb-6">
                <span class="section-number">02</span>
                <span class="h-px flex-1 bg-[#E5E2DD]"></span>
                <span class="text-xs font-medium uppercase tracking-[0.075em] text-[#544242]">Botanical Selection</span>
            </div>

            @if (count($ingredientRecs))
                <div class="flex gap-5 overflow-x-auto pb-4 custom-scrollbar" style="scrollbar-color:#DAC1BF transparent;">
                    @foreach ($ingredientRecs as $rec)
                        @php
                            $matchedFg = collect($frequencyGuidelines)->first(function ($val, $key) use ($rec) {
                                return $rec['item'] === $key || str_contains($rec['item'], $key) || str_contains($key, $rec['item']);
                            });
                            $usageLabel = match ($matchedFg['usage_time'] ?? null) {
                                'morning' => 'Pagi',
                                'night' => 'Malam',
                                'both' => 'Pagi & Malam',
                                default => null,
                            };
                            $cf = $rec['cf'] ?? 0;
                        @endphp
                        <article class="result-card shrink-0 w-[282px] min-w-[282px] p-6 flex flex-col">
                            <div class="flex items-start justify-between">
                                <span class="w-12 h-12 rounded-full bg-[#2D0003]/10 flex items-center justify-center text-[#2D0003]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s-6-5.686-6-10a6 6 0 1112 0c0 4.314-6 10-6 10z"></path>
                                    </svg>
                                </span>
                                <span class="text-xs font-semibold text-[#765C5D] px-2 py-1 rounded-full border border-[#DAC1BF]/50">
                                    CF {{ number_format($cf * 100, 0) }}%
                                </span>
                            </div>
                            <h3 class="font-garamond text-2xl font-medium text-[#2D0003] mt-5 leading-snug">
                                {{ $rec['item'] }}
                            </h3>
                            <p class="mt-3 text-sm leading-relaxed text-[#544242]/90 flex-1">
                                {{ $rec['message'] }}
                            </p>
                            <div class="mt-5 pt-4 border-t border-[#E5E2DD] flex items-center gap-2">
                                @if ($usageLabel)
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-[0.075em] text-[#544242]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#2D0003]"></span>
                                        {{ $usageLabel }}
                                    </span>
                                    @if (($matchedFg['max_frequency'] ?? null))
                                        <span class="text-[11px] text-[#765C5D]">&middot; maks {{ $matchedFg['max_frequency'] }}x/minggu</span>
                                    @endif
                                @else
                                    <span class="text-[11px] text-[#765C5D]">Dari hasil inferensi &amp; aturan pakai</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="result-card p-8">
                    <p class="text-sm text-[#544242]">Belum ada rekomendasi bahan aktif yang memenuhi kondisi profil Anda.</p>
                </div>
            @endif
        </section>

        <!-- Section 3: Strategic Focus -->
        <section class="pt-14">
            <div class="flex items-center gap-3 mb-6">
                <span class="section-number">03</span>
                <span class="h-px flex-1 bg-[#E5E2DD]"></span>
                <span class="text-xs font-medium uppercase tracking-[0.075em] text-[#544242]">Fokus Strategis</span>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <div class="p-8 border-l-2 border-[#2D0003]">
                    <h3 class="font-garamond text-3xl sm:text-4xl font-medium text-[#2D0003] leading-tight">
                        {{ $strategicFocus }}
                    </h3>
                    <p class="mt-4 text-[15px] leading-relaxed text-[#544242]">
                        {{ $inference['skin_type_summary'] ?? '' }}
                    </p>

                    @if (!empty($facts['retinol_tolerance']) && $facts['retinol_tolerance'] !== 'unknown')
                        <p class="mt-4 text-sm text-[#544242]">
                            Toleransi Retinol Anda: <strong class="text-[#2D0003]">{{ $retinolLabel }}</strong>.
                        </p>
                    @endif
                </div>

                <article class="result-card p-8">
                    <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.1em] text-[#2D0003] px-3 py-1.5 rounded-full bg-[#FAD7D8]/20 border border-[#FAD7D8]/50">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Pergeseran Rutinitas Direkomendasikan
                    </span>

                    <ul class="mt-6 space-y-4">
                        @forelse ($strategyRecs as $rec)
                            <li class="flex gap-4">
                                <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#2D0003]"></span>
                                <p class="text-[15px] leading-relaxed text-[#544242]">{{ $rec['message'] }}</p>
                            </li>
                        @empty
                            <li><p class="text-sm text-[#544242]">Belum ada arahan strategis tambahan.</p></li>
                        @endforelse
                    </ul>

                    @if (count($frequencyGuidelines))
                        <div class="mt-6 pt-5 border-t border-[#E5E2DD] space-y-3">
                            @foreach ($frequencyGuidelines as $fgName => $fg)
                                @if (!empty($fg['schedule_rule']))
                                    <p class="text-sm leading-relaxed text-[#544242]">
                                        <strong class="text-[#2D0003]">{{ $fgName }}</strong> &mdash; {{ $fg['schedule_rule'] }}
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </article>
            </div>
        </section>

        <!-- Section 4: Restrict List -->
        <section class="pt-14">
            <div class="flex items-center gap-3 mb-6">
                <span class="section-number">04</span>
                <span class="h-px flex-1 bg-[#E5E2DD]"></span>
                <span class="text-xs font-medium uppercase tracking-[0.075em] text-[#544242]">Daftar Pantangan</span>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <article class="result-card p-8">
                    <ul class="space-y-5">
                        @foreach ($restrictItems as $item)
                            <li class="flex gap-4">
                                <span class="shrink-0 mt-0.5 w-6 h-6 rounded-full border border-[#BA1A1A]/30 flex items-center justify-center text-[#BA1A1A]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </span>
                                <div>
                                    <p class="font-semibold text-[#BA1A1A]">{{ $item['name'] }}</p>
                                    <p class="mt-1 text-sm text-[#544242]">{{ $item['detail'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </article>

                <article class="result-card p-8 flex flex-col justify-between gap-6">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-[0.1em] text-[#544242]">Peringatan Sistem</span>
                        <ul class="mt-4 space-y-3">
                            @forelse ($safetyAlerts as $alert)
                                <li class="flex gap-3">
                                    <span class="shrink-0 mt-1 w-1.5 h-1.5 rounded-full bg-[#BA1A1A]"></span>
                                    <div>
                                        <p class="text-sm font-semibold text-[#2D0003]">{{ $alert['title'] }}</p>
                                        <p class="text-[13px] leading-relaxed text-[#544242]">{{ $alert['message'] }}</p>
                                    </div>
                                </li>
                            @empty
                                <li><p class="text-sm text-[#544242]">Tidak ada peringatan keamanan aktif.</p></li>
                            @endforelse
                        </ul>
                    </div>

                    <p class="text-xs text-[#765C5D] leading-relaxed">
                        Hasil ini bersifat informatif dan berdasarkan sesi konsultasi Anda. Konsultasikan setiap perubahan rutinitas dengan dokter kulit, terlebih jika Anda sedang hamil, menyusui, atau dalam perawatan dokter.
                    </p>
                </article>
            </div>
        </section>

        <!-- Meta footer + action links -->
        <footer class="mt-16 pt-8 border-t border-[#E5E2DD] flex flex-wrap items-center justify-between gap-4">
            <p class="text-xs text-[#765C5D]">
                {{ $inference['fired_rules_count'] ?? 0 }} aturan diaktifkan &middot; {{ count($ingredientRecs) }} rekomendasi bahan
            </p>
            <div class="flex items-center gap-5 text-xs font-semibold uppercase tracking-[0.075em] text-[#544242]">
                <a href="{{ route('user.consultation') }}" class="hover:text-[#2D0003] transition">Ulangi Konsultasi</a>
                <a href="{{ route('user.dashboard') }}" class="hover:text-[#2D0003] transition">Ritual Dashboard</a>
            </div>
        </footer>
    </div>

    <!-- Print / Download FAB -->
    <button onclick="window.print()" type="button"
            class="no-print fixed bottom-8 right-8 z-40 w-14 h-14 rounded-full bg-[#2D0003] text-white shadow-[0_8px_30px_rgba(45,0,3,0.35)] flex items-center justify-center hover:bg-[#1a0001] transition"
            title="Cetak / Unduh hasil">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
    </button>
@endsection