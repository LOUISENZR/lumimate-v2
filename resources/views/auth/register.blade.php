<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — LumiMate</title>

    <!-- Fonts: EB Garamond (heading) + Manrope (body) -->
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
<body class="min-h-screen antialiased">

    <!-- Background Image + Overlay -->
    <div class="fixed inset-0 -z-10">
        <img src="{{ asset('images/skin_scan.jpg') }}" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-cream/85"></div>
    </div>

    <!-- Registration Card -->
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-[480px] rounded-xl bg-cream/85 border border-cardborder/30 shadow-[0_10px_40px_rgba(77,14,18,0.05)] backdrop-blur-[6px] px-6 sm:px-14 py-12">

            <!-- Header -->
            <div class="text-center pb-8">
                <h1 class="font-garamond font-medium text-[56px] leading-[64px] tracking-[-1.12px] text-ink">
                    LumiMate
                </h1>
                <p class="font-manrope text-lg leading-7 text-inksoft mt-2">
                    Mulai ritual kulit Anda hari ini.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                @csrf

                <!-- Name -->
                <div class="flex flex-col gap-2">
                    <label for="name" class="font-manrope font-semibold text-sm leading-5 tracking-[0.7px] text-inksoft uppercase">
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Masukkan nama lengkap Anda"
                           class="w-full bg-white rounded-md px-2 py-[13px] font-manrope text-base leading-[22px] text-ink placeholder:text-[#6B7280] focus:outline-none focus:ring-1 focus:ring-ink/30 border border-cardborder/40 transition">
                    @error('name')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-2">
                    <label for="email" class="font-manrope font-semibold text-sm leading-5 tracking-[0.7px] text-inksoft uppercase">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="email@contoh.com"
                           class="w-full bg-white rounded-md px-2 py-[13px] font-manrope text-base leading-[22px] text-ink placeholder:text-[#6B7280] focus:outline-none focus:ring-1 focus:ring-ink/30 border border-cardborder/40 transition">
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-2">
                    <label for="password" class="font-manrope font-semibold text-sm leading-5 tracking-[0.7px] text-inksoft uppercase">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                               placeholder="Minimal 8 karakter"
                               class="w-full bg-white rounded-md px-2 py-[13px] pr-9 font-manrope text-base leading-[22px] text-ink placeholder:text-[#6B7280] focus:outline-none focus:ring-1 focus:ring-ink/30 border border-cardborder/40 transition">
                        <button type="button" id="togglePassword" aria-label="Tampilkan kata sandi"
                                class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center justify-center w-6 h-6 text-inksoft hover:text-ink transition">
                            <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19 12 19c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 5c4.756 0 8.773 2.662 10.065 7a13.959 13.959 0 01-1.875 3.818M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="flex flex-col gap-2">
                    <label for="password_confirmation" class="font-manrope font-semibold text-sm leading-5 tracking-[0.7px] text-inksoft uppercase">
                        Konfirmasi Kata Sandi
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="Ulangi kata sandi Anda"
                           class="w-full bg-white rounded-md px-2 py-[13px] font-manrope text-base leading-[22px] text-ink placeholder:text-[#6B7280] focus:outline-none focus:ring-1 focus:ring-ink/30 border border-cardborder/40 transition">
                </div>

                <!-- Terms -->
                <label class="flex items-start gap-2 pt-2 cursor-pointer select-none">
                    <input type="checkbox" name="terms" id="terms" value="1" class="mt-1 w-4 h-4 rounded bg-[#F6F3EE] border-[#877271] accent-ink focus:outline-none">
                    <span class="font-manrope font-medium text-xs leading-4 text-inksoft">
                        Saya menyetujui <span class="text-accentlink font-semibold">Syarat &amp; Ketentuan</span> LumiMate.
                    </span>
                </label>
                @error('terms')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit"
                            class="w-full bg-accentlink text-white font-manrope font-semibold text-sm leading-5 tracking-[0.7px] rounded-full py-4 shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)] hover:bg-ink transition">
                        Daftar Sekarang
                    </button>
                </div>

                <!-- Secondary Action -->
                <div class="pt-6 text-center">
                    <span class="font-manrope text-base leading-6 text-inksoft">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="ml-1 font-manrope font-semibold text-sm leading-5 tracking-[0.7px] text-accentlink hover:text-ink transition">
                        Masuk
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        if (toggle && password && eyeOpen && eyeClosed) {
            toggle.addEventListener('click', () => {
                const show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                eyeOpen.classList.toggle('hidden', show);
                eyeClosed.classList.toggle('hidden', !show);
            });
        }
    </script>
</body>
</html>