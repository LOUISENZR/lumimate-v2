<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LumiMate — Smart Skincare Routine Planner & Tracker')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS (Vite / CDN Fallback for instant rendering) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sceptre-red': '#4D0E12',
                        'dusty-rose': '#D8B7B8',
                        'muted-burgundy': '#8E3B46',
                        'warm-ivory': '#FAF7F2',
                        'soft-cream': '#F3ECE6',
                        'sidebar-bg': '#F4ECE6',
                        'sidebar-active': '#EADCD7',
                        'deep-charcoal': '#2B2525',
                        'warm-gray': '#766B6B',
                        'alert-rose': '#FBF0F0',
                        'alert-border': '#F5DADA',
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', '"Cormorant Garamond"', 'Georgia', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #FAF7F2;
            color: #2B2525;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-serif-luxury {
            font-family: 'Playfair Display', 'Cormorant Garamond', Georgia, serif;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D8B7B8;
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-warm-ivory flex flex-col md:flex-row antialiased selection:bg-dusty-rose selection:text-sceptre-red">

    <!-- Mobile Header (Visible on small screens) -->
    <div class="md:hidden flex items-center justify-between px-6 py-4 bg-sidebar-bg border-b border-soft-cream sticky top-0 z-50">
        <div>
            <span class="font-serif-luxury text-2xl font-bold text-sceptre-red tracking-wide">LumiMate</span>
            <span class="block text-xs text-warm-gray tracking-wider uppercase">Dasbor Ritual</span>
        </div>
        <button id="mobileMenuBtn" class="p-2 text-sceptre-red hover:bg-sidebar-active rounded-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-sidebar-bg flex flex-col justify-between p-6 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:static md:h-screen md:sticky md:top-0 border-r border-[#EFE5DE]">
        <div>
            <!-- Brand Logo (centered & enlarged) -->
            <div class="mb-8 flex items-center justify-center">
                <h1 class="font-serif-luxury text-4xl font-bold text-sceptre-red tracking-tight">LumiMate</h1>
            </div>

            <!-- Main Navigation Menu -->
            <nav class="space-y-1.5 font-medium text-sm">
                <!-- Dashboard (Active) -->
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-full transition-all duration-200 {{ request()->routeIs('user.dashboard') ? 'bg-sidebar-active text-sceptre-red font-semibold shadow-xs' : 'text-deep-charcoal hover:bg-sidebar-active/60 hover:text-sceptre-red' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Konsultasi -->
                <a href="{{ route('user.consultation') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-full transition-all duration-200 {{ request()->routeIs('user.consultation') ? 'bg-sidebar-active text-sceptre-red font-semibold' : 'text-deep-charcoal/80 hover:bg-sidebar-active/60 hover:text-sceptre-red' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Konsultasi</span>
                </a>

                <!-- Produk Saya -->
                <a href="{{ route('user.products') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-full transition-all duration-200 {{ request()->routeIs('user.products') ? 'bg-sidebar-active text-sceptre-red font-semibold' : 'text-deep-charcoal/80 hover:bg-sidebar-active/60 hover:text-sceptre-red' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Produk Saya</span>
                </a>

                <!-- Analisis Kandungan -->
                <a href="{{ route('user.ingredient.analysis') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-full transition-all duration-200 {{ request()->routeIs('user.ingredient.analysis') ? 'bg-sidebar-active text-sceptre-red font-semibold' : 'text-deep-charcoal/80 hover:bg-sidebar-active/60 hover:text-sceptre-red' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <span>Analisis Kandungan</span>
                </a>

                <!-- Pemeriksa Konflik -->
                <a href="{{ route('user.conflicts') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-full transition-all duration-200 {{ request()->routeIs('user.conflicts') ? 'bg-sidebar-active text-sceptre-red font-semibold' : 'text-deep-charcoal/80 hover:bg-sidebar-active/60 hover:text-sceptre-red' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>Pemeriksa Konflik</span>
                </a>

                <!-- Perencana Rutinitas -->
                <a href="{{ route('user.routine') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-full transition-all duration-200 {{ request()->routeIs('user.routine') ? 'bg-sidebar-active text-sceptre-red font-semibold' : 'text-deep-charcoal/80 hover:bg-sidebar-active/60 hover:text-sceptre-red' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Perencana Rutinitas</span>
                </a>

                <!-- Pelacak Harian -->
                <a href="{{ route('user.tracker') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-full transition-all duration-200 {{ request()->routeIs('user.tracker') ? 'bg-sidebar-active text-sceptre-red font-semibold' : 'text-deep-charcoal/80 hover:bg-sidebar-active/60 hover:text-sceptre-red' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Pelacak Harian</span>
                </a>

                <!-- Pemantauan Kemajuan -->
                <a href="{{ route('user.progress') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-full transition-all duration-200 {{ request()->routeIs('user.progress') ? 'bg-sidebar-active text-sceptre-red font-semibold' : 'text-deep-charcoal/80 hover:bg-sidebar-active/60 hover:text-sceptre-red' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span>Pemantauan Kemajuan</span>
                </a>
            </nav>
        </div>

        <!-- Bottom Sidebar Section -->
        <div class="pt-6 border-t border-[#EAE0D9] space-y-1 text-sm font-medium ">
            <!-- Pengaturan -->
            <a href="#pengaturan" class="flex items-center gap-3.5 px-4 py-2 text-deep-charcoal/75 hover:text-sceptre-red hover:bg-sidebar-active/50 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Pengaturan</span>
            </a>

            <!-- Profil -->
            <a href="#profil" class="flex items-center gap-3.5 px-4 py-2 text-deep-charcoal/75 hover:text-sceptre-red hover:bg-sidebar-active/50 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        <main class="flex-1 px-4 sm:px-8 py-8 md:py-10 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>

        <!-- Elegant Luxury Footer -->
        <footer class="mt-auto pt-16 pb-12 border-t border-[#EAE0D9] text-center text-xs text-warm-gray space-y-4">
            <div>
                <span class="font-serif-luxury text-2xl font-semibold text-sceptre-red tracking-wide">LumiMate</span>
                <p class="text-[11px] font-medium tracking-[0.2em] uppercase text-warm-gray mt-1">Ritual Adalah Segalanya.</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-[11px] font-medium tracking-wider uppercase text-deep-charcoal/70">
                <a href="#" class="hover:text-sceptre-red transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-sceptre-red transition">Ketentuan Layanan</a>
                <a href="#" class="hover:text-sceptre-red transition">Pengiriman</a>
                <a href="#" class="hover:text-sceptre-red transition">Pengembalian</a>
            </div>

            <p class="text-[11px] text-warm-gray pt-2">
                © {{ date('Y') }} LumiMate Rituals. Hak cipta dilindungi undang-undang.
            </p>
        </footer>
    </div>

    <!-- Mobile Drawer Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black/30 backdrop-blur-xs z-30 hidden md:hidden"></div>

    <script>
        const menuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');

        if (menuBtn && sidebar && overlay) {
            menuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>