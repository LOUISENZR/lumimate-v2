@extends('layouts.app')

@section('title', 'Koleksi Produk — LumiMate')

@section('content')
<div class="space-y-10">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-5">
        <div>
            <h2 class="font-serif-luxury text-4xl sm:text-5xl font-semibold text-sceptre-red tracking-tight">
                Koleksi Produk
            </h2>
            <p class="text-warm-gray text-base mt-2.5 max-w-xl leading-relaxed">
                Kelola seluruh produk skincare Anda di satu tempat. Tambahkan produk baru dan biarkan sistem mendeteksi kandungan aktifnya secara otomatis.
            </p>
        </div>

        <div class="relative">
            <input type="text" id="searchInput" placeholder="Cari produk..."
                   class="w-full sm:w-72 pl-10 pr-4 py-2.5 bg-white/60 border border-[#E5DCD3] rounded-full text-sm text-deep-charcoal placeholder-warm-gray focus:outline-none focus:border-sceptre-red focus:ring-1 focus:ring-sceptre-red/30 transition">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-warm-gray pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-2xl px-6 py-4 text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-alert-rose border border-alert-border text-muted-burgundy rounded-2xl px-6 py-4 text-sm font-medium flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Content: Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- ===================== LEFT COLUMN: Form + Summary ===================== -->
        <div class="lg:col-span-4 flex flex-col gap-6">

            <!-- Register New Product Form -->
            <div class="bg-soft-cream rounded-[2rem] p-8 border border-[#EBE1D9] shadow-xs">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-serif-luxury text-2xl font-medium text-sceptre-red leading-tight">
                        Daftarkan<br>Produk Baru
                    </h3>
                    <button type="button" id="toggleFormBtn"
                            class="w-10 h-10 flex items-center justify-center border border-[#E5E7EB] rounded-full text-sceptre-red hover:bg-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>

                <form id="productForm" action="{{ route('user.products.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Brand Name -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.6px] text-deep-charcoal mb-1.5">Nama Merek</label>
                        <input type="text" name="custom_brand" id="custom_brand"
                               placeholder="Cth. Somethinc, Avoskin, Wardah"
                               class="w-full bg-transparent border-b border-[#DCDAD5] py-2.5 text-sm text-deep-charcoal placeholder-warm-gray focus:outline-none focus:border-sceptre-red transition">
                    </div>

                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.6px] text-deep-charcoal mb-1.5">Judul Produk</label>
                        <input type="text" name="custom_name" id="custom_name" required
                               placeholder="Cth. Sky Tint Sunscreen, Brew Brew Serum"
                               class="w-full bg-transparent border-b border-[#DCDAD5] py-2.5 text-sm text-deep-charcoal placeholder-warm-gray focus:outline-none focus:border-sceptre-red transition">
                    </div>

                    <!-- Category & Usage Time (side by side) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.6px] text-deep-charcoal mb-1.5">Kategori</label>
                            <div class="relative">
                                <select name="custom_category" id="custom_category" required
                                        class="w-full appearance-none bg-transparent border-b border-[#DCDAD5] py-2.5 pr-8 text-sm text-deep-charcoal focus:outline-none focus:border-sceptre-red transition cursor-pointer">
                                    <option value="serum">Serum</option>
                                    <option value="cleanser">Cleanser</option>
                                    <option value="hydrating_toner">Toner</option>
                                    <option value="exfoliating_toner">Toner Eksfoliasi</option>
                                    <option value="moisturizer">Pelembap</option>
                                    <option value="sunscreen">Sunscreen</option>
                                    <option value="spot_treatment">Spot Treatment</option>
                                    <option value="eye_cream">Eye Cream</option>
                                    <option value="face_oil">Face Oil</option>
                                    <option value="other">Lainnya</option>
                                </select>
                                <svg class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-4 text-warm-gray pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-[0.6px] text-deep-charcoal mb-1.5">Penggunaan</label>
                            <div class="relative">
                                <select name="usage_time" id="usage_time" required
                                        class="w-full appearance-none bg-transparent border-b border-[#DCDAD5] py-2.5 pr-8 text-sm text-deep-charcoal focus:outline-none focus:border-sceptre-red transition cursor-pointer">
                                    <option value="morning">Pagi</option>
                                    <option value="night">Malam</option>
                                    <option value="both">Pagi & Malam</option>
                                </select>
                                <svg class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-4 text-warm-gray pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Ingredient Entry -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-[0.6px] text-deep-charcoal mb-1.5">Entri Bahan Manual</label>
                        <textarea name="custom_ingredients_raw" id="custom_ingredients_raw" rows="4"
                                  placeholder="Tempel daftar INCI di sini..."
                                  class="w-full bg-white/50 border border-[#DCDAD5] rounded-xl px-3 py-3 text-sm text-deep-charcoal placeholder-warm-gray focus:outline-none focus:border-sceptre-red focus:ring-1 focus:ring-sceptre-red/20 transition resize-none"></textarea>
                        <p class="text-[11px] text-warm-gray mt-1.5 leading-relaxed">Pisahkan dengan koma. Sistem akan mendeteksi bahan aktif secara otomatis.</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-sceptre-red text-white text-sm font-semibold py-3 rounded-xl hover:bg-[#3B0A0E] transition-all duration-200 shadow-sm hover:shadow-md">
                        <span>Tambahkan ke Koleksi</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Summary / Info Card -->
            <div class="relative rounded-[2rem] overflow-hidden shadow-xs border border-[#EBE1D9] h-64 bg-gradient-to-br from-sceptre-red via-[#5A1318] to-[#3B0A0E]">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PHBhdGggZD0iTTM2IDM0djZoNnYtNmgtNnptMC0zMHY2aDZ2LTZoLTZ6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-40"></div>
                <div class="relative h-full flex flex-col justify-end p-8">
                    <p class="text-[10px] font-semibold uppercase tracking-[1px] text-white/70 mb-1.5">Ringkasan Koleksi</p>
                    <h4 class="font-serif-luxury text-2xl font-medium text-white leading-snug mb-4">
                        {{ $activeCount }} produk aktif dengan {{ $totalIngredients }} bahan aktif terdeteksi.
                    </h4>
                    @if($riskyCount > 0)
                        <div class="flex items-center gap-2 text-white/90">
                            <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="text-xs font-medium">{{ $riskyCount }} kombinasi berisiko terdeteksi</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-white/90">
                            <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs font-medium">Tidak ada konflik berisiko</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ===================== RIGHT COLUMN: Product Collection ===================== -->
        <div class="lg:col-span-8 flex flex-col gap-6">

            <!-- Collection Header -->
            <div class="flex items-center justify-between">
                <h3 class="font-serif-luxury text-3xl font-medium text-sceptre-red tracking-tight">Koleksi Saya</h3>
                <div class="flex items-center gap-2">
                    <button id="viewGrid" class="p-2 rounded-lg bg-soft-cream border border-[#EBE1D9] text-sceptre-red transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </button>
                    <button id="viewList" class="p-2 rounded-lg text-deep-charcoal/60 hover:bg-soft-cream/60 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Product Cards Grid -->
            @if($userProducts->isEmpty())
                <div class="bg-white rounded-[2rem] border border-[#EBE1D9] shadow-xs p-16 text-center">
                    <div class="w-20 h-20 mx-auto rounded-full bg-soft-cream flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-dusty-rose" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h4 class="font-serif-luxury text-2xl font-medium text-deep-charcoal mb-2">Belum Ada Produk</h4>
                    <p class="text-sm text-warm-gray max-w-sm mx-auto leading-relaxed">
                        Mulai tambahkan produk skincare Anda menggunakan formulir di sebelah kiri. Sistem akan mendeteksi bahan aktif secara otomatis.
                    </p>
                </div>
            @else
                <div id="productGrid" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($userProducts as $product)
                        @php
                            $brand = $product->display_brand;
                            $name = $product->display_name;
                            $category = $product->display_category;

                            $categoryLabels = [
                                'cleanser' => 'Cleanser',
                                'hydrating_toner' => 'Toner',
                                'exfoliating_toner' => 'Eksfoliasi',
                                'serum' => 'Serum',
                                'spot_treatment' => 'Spot Care',
                                'eye_cream' => 'Eye Cream',
                                'moisturizer' => 'Pelembap',
                                'face_oil' => 'Face Oil',
                                'sunscreen' => 'Sunscreen',
                                'other' => 'Lainnya',
                            ];

                            $timeLabels = [
                                'morning' => 'Pagi',
                                'night' => 'Malam',
                                'both' => 'Pagi & Malam',
                            ];

                            $detectedIngredients = $product->product
                                ? $product->product->ingredients
                                : $product->ingredients;

                            $ingredientCount = $detectedIngredients->count();
                            $maxDisplay = 6;
                        @endphp

                        <div class="product-card bg-white rounded-2xl p-6 border border-[#EBE1D9] shadow-xs hover:shadow-sm transition-all duration-200 flex flex-col gap-4"
                             data-brand="{{ strtolower($brand) }}"
                             data-name="{{ strtolower($name) }}">

                            <!-- Card Header: Product Info + Delete -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-4 min-w-0">
                                    <!-- Product Icon -->
                                    <div class="w-12 h-12 rounded-xl bg-soft-cream border border-[#EBE1D9] flex items-center justify-center flex-shrink-0">
                                        @if($category === 'sunscreen')
                                            <svg class="w-6 h-6 text-sceptre-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="4" stroke-width="2"></circle>
                                                <path stroke-linecap="round" stroke-width="2" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32l1.41-1.41"></path>
                                            </svg>
                                        @elseif($category === 'serum')
                                            <svg class="w-6 h-6 text-sceptre-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                        @elseif($category === 'moisturizer')
                                            <svg class="w-6 h-6 text-sceptre-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-sceptre-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <h4 class="font-medium text-deep-charcoal text-base truncate">{{ $name }}</h4>
                                        <p class="text-xs text-warm-gray truncate">{{ $brand }}</p>
                                    </div>
                                </div>

                                <!-- Delete Button -->
                                <form action="{{ route('user.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini dari koleksi?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg text-warm-gray hover:text-red-500 hover:bg-red-50 transition flex-shrink-0"
                                            title="Hapus produk">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Badges: Category + Usage Time -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md bg-soft-cream text-[10px] font-semibold uppercase tracking-wider text-sceptre-red">
                                    {{ $categoryLabels[$category] ?? $category }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-md bg-soft-cream text-[10px] font-semibold uppercase tracking-wider text-sceptre-red">
                                    {{ $timeLabels[$product->usage_time] ?? $product->usage_time }}
                                </span>
                            </div>

                            <!-- Detected Ingredients (if any) -->
                            @if($ingredientCount > 0)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($detectedIngredients->take($maxDisplay) as $ingredient)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-[#F9F5F0] text-[10px] font-medium text-warm-gray border border-[#F0E8E0]">
                                            {{ $ingredient->ingredient_name }}
                                        </span>
                                    @endforeach
                                    @if($ingredientCount > $maxDisplay)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-[#F9F5F0] text-[10px] font-medium text-warm-gray border border-[#F0E8E0]">
                                            +{{ $ingredientCount - $maxDisplay }} lagi
                                        </span>
                                    @endif
                                </div>
                            @elseif($product->custom_ingredients_raw)
                                <p class="text-xs text-warm-gray italic leading-relaxed line-clamp-2">{{ Str::limit($product->custom_ingredients_raw, 80) }}</p>
                            @endif

                            <!-- Ingredient Detection Progress -->
                            <div class="pt-3 border-t border-[#F0E8E0]">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="flex items-center gap-1.5 text-warm-gray">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-[11px] font-medium">{{ $ingredientCount }} bahan aktif terdeteksi</span>
                                    </div>
                                    @if($ingredientCount > 0)
                                        <span class="text-[10px] font-bold text-sceptre-red uppercase tracking-wider">Terkini</span>
                                    @endif
                                </div>
                                <div class="w-full h-1 bg-[#F0E8E0] rounded-full overflow-hidden">
                                    <div class="h-full bg-sceptre-red rounded-full transition-all duration-500"
                                         style="width: {{ min(100, $ingredientCount * 16) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Footer Navigation -->
    <div class="border-t border-[#EBE1D9] pt-10 pb-4">
        <h3 class="font-serif-luxury text-2xl font-medium text-sceptre-red text-center mb-5">Navigasi Cepat</h3>
        <div class="flex flex-wrap justify-center gap-5 text-[11px] font-semibold uppercase tracking-[0.55px] text-deep-charcoal">
            <a href="{{ route('user.dashboard') }}" class="hover:text-sceptre-red transition">Dashboard</a>
            <a href="{{ route('user.consultation') }}" class="hover:text-sceptre-red transition">Konsultasi</a>
            <a href="{{ route('user.ingredient.analysis') }}" class="hover:text-sceptre-red transition">Analisis Kandungan</a>
            <a href="{{ route('user.conflicts') }}" class="hover:text-sceptre-red transition">Pemeriksa Konflik</a>
        </div>
        <p class="text-center text-[11px] text-warm-gray mt-4">
            &copy; {{ date('Y') }} LumiMate Rituals. Hak cipta dilindungi undang-undang.
        </p>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const productCards = document.querySelectorAll('.product-card');

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                productCards.forEach(card => {
                    const brand = card.dataset.brand || '';
                    const name = card.dataset.name || '';
                    const match = brand.includes(query) || name.includes(query);
                    card.style.display = match ? '' : 'none';
                });
            });
        }

        // View toggle (Grid/List)
        const gridBtn = document.getElementById('viewGrid');
        const listBtn = document.getElementById('viewList');
        const grid = document.getElementById('productGrid');

        if (gridBtn && listBtn && grid) {
            gridBtn.addEventListener('click', () => {
                grid.classList.remove('grid-cols-1');
                grid.classList.add('grid-cols-1', 'md:grid-cols-2');
                gridBtn.classList.add('bg-soft-cream', 'border', 'border-[#EBE1D9]', 'text-sceptre-red');
                gridBtn.classList.remove('text-deep-charcoal/60');
                listBtn.classList.remove('bg-soft-cream', 'border', 'border-[#EBE1D9]', 'text-sceptre-red');
                listBtn.classList.add('text-deep-charcoal/60');
            });

            listBtn.addEventListener('click', () => {
                grid.classList.remove('md:grid-cols-2');
                grid.classList.add('md:grid-cols-1');
                listBtn.classList.add('bg-soft-cream', 'border', 'border-[#EBE1D9]', 'text-sceptre-red');
                listBtn.classList.remove('text-deep-charcoal/60');
                gridBtn.classList.remove('bg-soft-cream', 'border', 'border-[#EBE1D9]', 'text-sceptre-red');
                gridBtn.classList.add('text-deep-charcoal/60');
            });
        }

        // Auto-capitalize brand name
        const brandInput = document.getElementById('custom_brand');
        if (brandInput) {
            brandInput.addEventListener('blur', (e) => {
                e.target.value = e.target.value.replace(/\b\w/g, c => c.toUpperCase());
            });
        }
    });
</script>
@endpush
