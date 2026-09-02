<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LumiMate — Smart Skincare Routine Planner & Tracker</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#2D0003',
                        inksoft: '#544242',
                        cream: '#FCF9F4',
                        cardborder: '#DAC1BF',
                        accentlink: '#4D0E12',
                        rosepale: '#FAD7D8',
                    },
                    fontFamily: {
                        garamond: ['"EB Garamond"', 'Georgia', 'serif'],
                        manrope: ['Manrope', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: #FCF9F4;
            color: #2D0003;
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen antialiased selection:bg-rosepale selection:text-ink">

    <!-- Nav -->
    <header class="sticky top-0 z-40 bg-cream/95 backdrop-blur border-b border-[#EFE5DE]">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="font-garamond text-2xl font-medium text-ink leading-8">LumiMate</a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-inksoft">
                <a href="#manfaat" class="hover:text-ink transition">Manfaat</a>
                <a href="#metode" class="hover:text-ink transition">Metode</a>
                <a href="#alam" class="hover:text-ink transition">Botanical</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('user.dashboard') }}" class="inline-flex px-5 py-2.5 rounded-full bg-ink text-white text-sm font-semibold tracking-wide hover:bg-[#1a0001] transition">Ritual Saya</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-inksoft hover:text-ink transition">Masuk</a>
                    <a href="{{ route('register') }}" class="inline-flex px-5 py-2.5 rounded-full bg-ink text-white text-sm font-semibold tracking-wide hover:bg-[#1a0001] transition">Mulai Gratis</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="max-w-6xl mx-auto px-6 pt-16 pb-24 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.15em] text-accentlink px-3 py-1.5 rounded-full bg-rosepale/30 border border-rosepale/60">
                Expert System &mdash; Expert System Skin
            </span>
            <h1 class="font-garamond font-medium text-[44px] sm:text-[64px] leading-[1.05] tracking-[-0.02em] text-ink mt-6">
                Ritual kulit Anda,
                <span class="italic">dibaca</span> oleh sistem.
            </h1>
            <p class="mt-6 text-lg leading-relaxed text-inksoft max-w-[480px]">
                LumiMate menganalisis jenis &amp; masalah kulit Anda melalui konsultasi berjenjang (BSTI), lalu menyusun rutinitas personalized — bahan aktif, frekuensi, hingga pantangan yang aman.
            </p>
            <div class="mt-9 flex flex-wrap items-center gap-4">
                @auth
                    <a href="{{ route('user.consultation') }}" class="inline-flex px-8 py-3.5 rounded-full bg-ink text-white font-semibold tracking-wide hover:bg-[#1a0001] transition">Analisis Kulit Saya</a>
                    <a href="{{ route('user.dashboard') }}" class="text-sm font-semibold text-inksoft hover:text-ink transition">Buka Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex px-8 py-3.5 rounded-full bg-ink text-white font-semibold tracking-wide hover:bg-[#1a0001] transition">Mulai Konsultasi Gratis</a>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-inksoft hover:text-ink transition">Saya sudah punya akun</a>
                @endauth
            </div>
        </div>

        <div class="relative">
            <div class="rounded-3xl overflow-hidden shadow-[0_10px_40px_rgba(77,14,18,0.05)]">
                <img src="{{ asset('images/skin_scan.jpg') }}" alt="Analisis kulit LumiMate" class="w-full h-[460px] object-cover">
            </div>
            <div class="absolute -bottom-6 -left-6 rounded-2xl bg-white border border-cardborder/40 shadow-[0_10px_40px_rgba(77,14,18,0.08)] px-6 py-4">
                <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-inksoft">Profil Terdeteksi</p>
                <p class="font-garamond text-xl font-medium text-ink mt-1">Kombinasi Sensitif</p>
            </div>
        </div>
    </section>

    <!-- Manfaat -->
    <section id="manfaat" class="bg-[#F6F3EE] border-y border-[#EFE5DE]">
        <div class="max-w-6xl mx-auto px-6 py-20">
            <div class="text-center mb-12">
                <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-inksoft">Mengapa LumiMate</span>
                <h2 class="font-garamond text-4xl sm:text-5xl font-medium text-ink mt-3 tracking-[-0.01em]">Analisis yang bisa dipercaya</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
$feats = [
                        ['t' => 'Konsultasi 8 Langkah', 'd' => 'Identifikasi tipe kulit, masalah utama, sensitivitas, hingga kondisi khusus seperti kehamilan & alergi.'],
                        ['t' => 'Mesin Inferensi (CF)', 'd' => 'Aturan pakar forward chaining menghitung tingkat kepastian (Certainty Factor) setiap rekomendasi bahan aktif.'],
                        ['t' => 'Pemeriksa Konflik', 'd' => 'Deteksi pasangan bahan berisiko (mis. Retinol + AHA) sebelum Anda menumpuknya dalam satu malam.'],
                        ['t' => 'Rutinitas Personal', 'd' => 'Susunan langkah pagi-malam disesuaikan frekuensi & skin cycling untuk hasil maksimal tanpa iritasi.'],
                        ['t' => 'Pantangan Aman', 'd' => 'Filter keamanan otomatis untuk ibu hamil, alergi fragrance, dan kulit sangat reaktif.'],
                        ['t' => 'Pelacak Harian', 'd' => 'Pantau kepatuhan ritual dan konsistensi kemajuan kulit dari waktu ke waktu.'],
                    ];
                @endphp
                @foreach ($feats as $f)
                    <div class="rounded-2xl bg-white border border-cardborder/40 px-7 py-7">
                        <span class="w-10 h-10 rounded-full bg-ink/5 flex items-center justify-center">
                            <svg class="w-5 h-5 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5h18M6 14a2 2 0 012 2m10-2a2 2 0 01-2 2m-8 0v3h12v-3M20 14H4l2-6h12l2 6z"></path>
                            </svg>
                        </span>
                        <h3 class="font-garamond text-xl font-medium text-ink mt-5">{{ $f['t'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-inksoft">{{ $f['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Metode -->
    <section id="metode" class="max-w-6xl mx-auto px-6 py-24">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-inksoft">Cara Kerja</span>
                <h2 class="font-garamond text-4xl sm:text-5xl font-medium text-ink mt-3 tracking-[-0.01em]">
                    Dari kuisioner menjadi ritual.
                </h2>
                <p class="mt-5 text-lg leading-relaxed text-inksoft max-w-[520px]">
                    Jawab 8 pertanyaan singkat. Sistem pakar menerjemahkan jawaban Anda menjadi profil kulit dan rekomendasi bahan aktif dengan bobot kepastian.
                </p>
            </div>

            <ol class="space-y-0">
                @php
                    $steps = [
                        ['n' => '01', 't' => 'Konsultasi', 'd' => 'Jawab pertanyaan seputar sebum, pori, riwayat reaksi, dan masalah kulit.'],
                        ['n' => '02', 't' => 'Inferensi', 'd' => 'Mesin mencocokkan fakta Anda dengan 21 aturan pakar (R01–R12, F01–F09).'],
                        ['n' => '03', 't' => 'Ritual Personal', 'd' => 'Terima rekomendasi bahan, frekuensi, strategi, serta daftar pantangan Anda.'],
                    ];
                @endphp
                @foreach ($steps as $s)
                    <li class="flex gap-6 pb-8 relative">
                        @if (!$loop->last)
                            <span class="absolute left-[15px] top-10 bottom-0 w-px bg-cardborder/50"></span>
                        @endif
                        <span class="shrink-0 w-8 h-8 rounded-full bg-ink text-white flex items-center justify-center font-garamond text-sm font-medium relative z-10">{{ $s['n'] }}</span>
                        <div>
                            <h3 class="font-garamond text-2xl font-medium text-ink">{{ $s['t'] }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-inksoft">{{ $s['d'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <!-- Final CTA -->
    <section id="alam" class="bg-ink text-cream">
        <div class="max-w-6xl mx-auto px-6 py-24 text-center">
            <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#D8B7B8]">Ritual Adalah Segalanya</span>
            <h2 class="font-garamond text-4xl sm:text-6xl font-medium mt-4 tracking-[-0.01em]">
                Mulai membaca kulit Anda.
            </h2>
            <p class="mt-5 text-lg text-[#E8DCD9] max-w-[520px] mx-auto">
                Gratis untuk memulai. Tidak perlu kartu, cukup jujur tentang kulit Anda.
            </p>
            <div class="mt-9 flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('user.consultation') }}" class="inline-flex px-8 py-3.5 rounded-full bg-white text-ink font-semibold tracking-wide hover:bg-[#F0EDE9] transition">Lanjutkan Konsultasi</a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex px-8 py-3.5 rounded-full bg-white text-ink font-semibold tracking-wide hover:bg-[#F0EDE9] transition">Daftar Sekarang</a>
                    <a href="{{ route('login') }}" class="inline-flex px-8 py-3.5 rounded-full border border-[#E8DCD9] text-white font-semibold tracking-wide hover:bg-white/10 transition">Masuk</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-cream">
        <div class="max-w-6xl mx-auto px-6 py-12 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <span class="font-garamond text-2xl font-medium text-ink">LumiMate</span>
                <p class="text-[11px] font-medium tracking-[0.2em] uppercase text-inksoft mt-1">Ritual Adalah Segalanya.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-xs font-semibold uppercase tracking-[0.1em] text-inksoft">
                <a href="{{ route('login') }}" class="hover:text-ink transition">Kebijakan Privasi</a>
                <span>© {{ date('Y') }} LumiMate</span>
                <a href="{{ route('register') }}" class="hover:text-ink transition">Ketentuan Layanan</a>
            </div>
        </div>
    </footer>
</body>
</html>