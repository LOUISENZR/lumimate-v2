@extends('layouts.app')

@section('title', 'Dashboard Ritual — LumiMate')

@section('content')
<div class="space-y-9">

    <!-- Top Header: Greeting & Quick Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
        <div>
            <h2 class="font-serif-luxury text-4xl sm:text-5xl font-semibold text-sceptre-red tracking-tight mt-1.5">
                {{ $greetingTime }}, {{ $firstName }}
            </h2>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('user.tracker') }}" class="inline-flex items-center justify-center gap-3 px-7 py-3.5 bg-sceptre-red text-white text-base font-medium rounded-full shadow-sm hover:bg-[#3B0A0E] transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
                <span>Catat Ritual Malam Ini</span>
            </a>

            <a href="#notifikasi" class="inline-flex items-center justify-center w-12 h-12 bg-white border border-[#EFE8E2] text-warm-gray hover:text-sceptre-red hover:border-sceptre-red rounded-full shadow-xs transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- ================= ROW 1: KONDISI KULIT + (METRIK RINGKAS & DIAGRAM HIDRASI) side by side ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

        <!-- Card: Kondisi Kulit -->
        <div class="lg:col-span-5 bg-soft-cream rounded-[2.75rem] p-9 border border-[#EBE1D9] shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="font-serif-luxury text-4xl font-medium text-sceptre-red tracking-tight mb-8">
                    Kondisi Kulit
                </h3>

                <div class="space-y-5 text-base">
                    <!-- Tipe Kulit -->
                    <div class="flex items-baseline justify-between border-b border-[#E8DDD4] pb-4">
                        <span class="text-warm-gray text-sm font-semibold">Tipe</span>
                        <span class="font-bold text-deep-charcoal text-right text-lg">{{ $skinSynthesis['skin_type'] }}</span>
                    </div>

                    <!-- Masalah Utama -->
                    <div class="flex items-baseline justify-between border-b border-[#E8DDD4] pb-4">
                        <span class="text-warm-gray text-sm font-semibold">Masalah Utama</span>
                        <span class="font-bold text-deep-charcoal text-right text-lg">{{ $skinSynthesis['primary_concern'] }}</span>
                    </div>

                    <!-- Sensitivitas -->
                    <div class="flex items-center justify-between pt-1">
                        <span class="text-warm-gray text-sm font-semibold">Sensitivitas</span>
                        <span class="font-bold text-sceptre-red text-right flex items-center gap-2 text-base sm:text-lg">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" stroke-width="2"></circle>
                                <path stroke-linecap="round" stroke-width="2" d="M12 7v5l3 3"></path>
                            </svg>
                            {{ $skinSynthesis['sensitivity'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Realtime Photo Card with Floating Date Badge -->
            <div class="mt-8 rounded-3xl overflow-hidden relative shadow-inner flex-1 min-h-[16rem] bg-[#E8DDD4] border border-[#E2D5CB]">
                <img src="{{ $skinSynthesis['last_scan_image'] }}" alt="Hasil Pemindaian Kulit Terkini" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/10 to-transparent pointer-events-none"></div>
                <div class="absolute bottom-4 left-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-black/65 backdrop-blur-md text-xs font-bold tracking-widest text-white uppercase border border-white/20">
                        PEMINDAIAN TERAKHIR: {{ $skinSynthesis['last_scan'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Right Column: Metric Cards (moved up) + Konsistensi Hidrasi -->
        <div class="lg:col-span-7 flex flex-col gap-8">

            <!-- Metric Cards Row (previously Row 3, now above Konsistensi Hidrasi) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                <!-- 1. Kemajuan Ritual (Circular Progress) -->
                <div class="bg-white rounded-[2.5rem] p-6 border border-[#EFE8E2] shadow-xs flex flex-col items-center justify-center text-center">
                    <div class="relative w-24 h-24 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <!-- Background Circle -->
                            <path class="text-[#F1E8E2]" stroke-width="3.5" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <!-- Progress Stroke -->
                            <path class="text-sceptre-red transition-all duration-1000 ease-out" stroke-dasharray="{{ $progressPercentage }}, 100" stroke-width="3.5" stroke-linecap="round" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="font-serif-luxury text-xl font-bold text-deep-charcoal leading-none">{{ $progressPercentage }}%</span>
                            <span class="text-[9px] font-bold text-warm-gray uppercase tracking-wider mt-1">HARI INI</span>
                        </div>
                    </div>

                    <h4 class="font-bold text-deep-charcoal text-sm mt-3.5">Kemajuan Ritual</h4>
                    <p class="text-xs text-warm-gray mt-1">Langkah {{ $completedSteps }} dari {{ $totalSteps }} Selesai</p>
                </div>

                <!-- 2. Runtun Hari (Streak Counter) -->
                <div class="bg-soft-cream/80 rounded-[2.5rem] p-6 border border-[#EFE8E2] shadow-xs flex flex-col items-center justify-center text-center">
                    <div class="w-11 h-11 rounded-full bg-[#EADFD8] flex items-center justify-center text-sceptre-red mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                        </svg>
                    </div>

                    <span class="font-serif-luxury text-4xl font-bold text-deep-charcoal mt-1">{{ $streakCount }}</span>
                    <span class="text-[10px] font-bold tracking-widest uppercase text-muted-burgundy mt-1">RUNTUN HARI</span>
                    <p class="text-xs text-warm-gray mt-1.5">Anda bersinar, {{ $firstName }}.</p>
                </div>

                <!-- 3. Ritual Mingguan (Mini Bar Graph) -->
                <div class="bg-white rounded-[2.5rem] p-6 border border-[#EFE8E2] shadow-xs flex flex-col justify-between">
                    <h4 class="font-bold text-deep-charcoal text-sm mb-3">Ritual Mingguan</h4>

                    <!-- 7-Day Mini Bars -->
                    <div class="flex items-end justify-between gap-1.5 h-16 px-1">
                        @foreach($weeklyData as $item)
                            <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                                <div class="w-full rounded-t-md transition-all duration-500 {{ $item['is_active'] ? 'bg-sceptre-red' : 'bg-[#EADFD8]' }}" style="height: {{ $item['rate'] }}%;"></div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-xs text-warm-gray text-center font-medium mt-3">
                        4.2 Ritual/Minggu Rata-rata.
                    </p>
                </div>

            </div>

            <!-- Quick Action Buttons (moved here, above Konsistensi Hidrasi) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <!-- 1. Konsultasi -->
                <a href="{{ route('user.consultation') }}" class="bg-white rounded-2xl py-3.5 px-4 border border-[#EFE8E2] hover:border-sceptre-red hover:shadow-xs transition-all flex items-center justify-center gap-2.5 group text-center">
                    <svg class="w-5 h-5 text-warm-gray group-hover:text-sceptre-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-wider text-deep-charcoal group-hover:text-sceptre-red">KONSULTASI</span>
                </a>

                <!-- 2. Tambah Alat / Produk -->
                <a href="{{ route('user.products') }}" class="bg-white rounded-2xl py-3.5 px-4 border border-[#EFE8E2] hover:border-sceptre-red hover:shadow-xs transition-all flex items-center justify-center gap-2.5 group text-center">
                    <svg class="w-5 h-5 text-warm-gray group-hover:text-sceptre-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-wider text-deep-charcoal group-hover:text-sceptre-red">TAMBAH ALAT</span>
                </a>

                <!-- 3. Rutinitas -->
                <a href="{{ route('user.routine') }}" class="bg-white rounded-2xl py-3.5 px-4 border border-[#EFE8E2] hover:border-sceptre-red hover:shadow-xs transition-all flex items-center justify-center gap-2.5 group text-center">
                    <svg class="w-5 h-5 text-warm-gray group-hover:text-sceptre-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-wider text-deep-charcoal group-hover:text-sceptre-red">RUTINITAS</span>
                </a>

                <!-- 4. Kemajuan -->
                <a href="{{ route('user.tracker') }}" class="bg-white rounded-2xl py-3.5 px-4 border border-[#EFE8E2] hover:border-sceptre-red hover:shadow-xs transition-all flex items-center justify-center gap-2.5 group text-center">
                    <svg class="w-5 h-5 text-warm-gray group-hover:text-sceptre-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-wider text-deep-charcoal group-hover:text-sceptre-red">KEMAJUAN</span>
                </a>
            </div>

            <!-- Card: Konsistensi Hidrasi (Diagram) -->
            <div class="bg-white rounded-[2.75rem] p-9 border border-[#EFE8E2] shadow-xs flex flex-col flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-7">
                    <div>
                        <h3 class="font-serif-luxury text-3xl font-bold text-deep-charcoal tracking-tight">Konsistensi Hidrasi</h3>
                        <p class="text-sm text-warm-gray font-medium mt-1">Umpan balik bio-metrik selama 30 hari terakhir</p>
                    </div>

                    <div>
                        <span class="inline-flex items-center px-5 py-2 rounded-full bg-soft-cream text-xs font-bold tracking-wider text-warm-gray uppercase">
                            TAMPILAN BULANAN
                        </span>
                    </div>
                </div>

                <!-- Chart.js Canvas Area -->
                <div class="relative flex-1 min-h-[18rem] w-full">
                    <canvas id="hydrationChart"></canvas>
                </div>
            </div>

        </div>

    </div>

    <!-- ================= ROW 2: PERINGATAN KANDUNGAN ================= -->
    <div class="bg-alert-rose rounded-3xl p-7 border border-alert-border flex items-start gap-5 shadow-xs">
        <div class="p-3.5 bg-[#F6DCDC] text-muted-burgundy rounded-2xl flex-shrink-0 mt-0.5">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div>
            <h4 class="text-base font-bold text-muted-burgundy">Peringatan Kandungan</h4>
            <p class="text-sm text-sceptre-red/85 font-medium mt-1.5 leading-relaxed">
                {{ $conflictWarning }}
            </p>
        </div>
    </div>


</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('hydrationChart').getContext('2d');

        // Create gradient fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(216, 183, 184, 0.45)'); // Dusty rose
        gradient.addColorStop(1, 'rgba(250, 247, 242, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['MINGGU 1', 'MINGGU 2', 'MINGGU 3', 'MINGGU 4'],
                datasets: [{
                    label: 'Bio-metrik (%)',
                    data: [58, 62, 86, 52],
                    borderColor: '#9E7E7E',
                    borderWidth: 2.5,
                    backgroundColor: gradient,
                    tension: 0.45,
                    fill: true,
                    pointBackgroundColor: ['transparent', 'transparent', '#4D0E12', 'transparent'],
                    pointBorderColor: ['transparent', 'transparent', '#FAF7F2', 'transparent'],
                    pointBorderWidth: [0, 0, 3, 0],
                    pointRadius: [0, 0, 7, 0],
                    pointHoverRadius: [5, 5, 9, 5],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#4D0E12',
                        titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 11 },
                        padding: 11,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                if (context.dataIndex === 2) {
                                    return 'Titik Optimal: ' + context.parsed.y + '% Konsistensi';
                                }
                                return context.parsed.y + '% Konsistensi';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#766B6B',
                            font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                            padding: 10,
                        },
                        border: { display: false }
                    },
                    y: {
                        min: 30,
                        max: 100,
                        grid: {
                            color: '#F3ECE6',
                            drawBorder: false,
                        },
                        ticks: { display: false },
                        border: { display: false }
                    }
                }
            },
            plugins: [{
                afterDraw: chart => {
                    const ctx = chart.ctx;
                    const meta = chart.getDatasetMeta(0);
                    const point = meta.data[2]; // Week 3 Point

                    if (point) {
                        ctx.save();
                        // Draw "Titik Optimal" floating pill badge
                        const text = "Titik Optimal";
                        ctx.font = "bold 10px 'Plus Jakarta Sans'";
                        const textWidth = ctx.measureText(text).width;
                        const boxWidth = textWidth + 18;
                        const boxHeight = 22;
                        const x = point.x - (boxWidth / 2);
                        const y = point.y - 32;

                        // Pill background
                        ctx.fillStyle = "#FFFFFF";
                        ctx.strokeStyle = "#EAE0D9";
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.roundRect(x, y, boxWidth, boxHeight, 11);
                        ctx.fill();
                        ctx.stroke();

                        // Pill text
                        ctx.fillStyle = "#2B2525";
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.fillText(text, point.x, y + (boxHeight / 2));
                        ctx.restore();
                    }
                }
            }]
        });
    });
</script>
@endpush