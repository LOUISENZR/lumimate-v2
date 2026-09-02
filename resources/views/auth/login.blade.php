<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — LumiMate</title>

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
                        inputbg: '#F0EDE9',
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

    <!-- Login Split -->
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- Left: Brand + Login Card -->
        <div class="w-full lg:w-[480px] xl:w-[560px] shrink-0 flex flex-col justify-center items-center px-6 py-14">
            <div class="w-full max-w-[448px] flex flex-col">

                <!-- Brand Logo Area -->
                <div class="flex flex-col gap-2 mb-4">
                    <h1 class="font-garamond font-medium text-[56px] leading-[64px] tracking-[-1.12px] text-center text-ink">
                        LumiMate
                    </h1>
                    <p class="font-manrope text-base leading-6 text-center text-inksoft">
                        Rahasia kulit sehat, dalam satu ritual.
                    </p>
                </div>

                <!-- Login Card -->
                <div class="rounded-xl bg-cream/90 border border-ink/10 shadow-[0_20px_25px_-5px_rgba(0,0,0,0.1),0_8px_10px_-6px_rgba(0,0,0,0.1)] backdrop-blur-[6px] px-8 pb-8">

                    <!-- Card Header -->
                    <div class="pt-8 pb-4 text-center">
                        <h2 class="font-garamond font-medium text-2xl leading-8 text-ink">
                            Selamat Datang
                        </h2>
                        <p class="font-manrope text-base leading-6 text-inksoft mt-2">
                            Masuk untuk melanjutkan ritual Anda.
                        </p>
                    </div>

                    <!-- Global Error Alert -->
                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                        @csrf

                        <!-- Email -->
                        <div class="flex flex-col gap-2">
                            <label for="email" class="font-manrope font-semibold text-sm leading-5 tracking-[0.7px] text-inksoft">
                                EMAIL
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                   placeholder="nama@email.com"
                                   class="w-full bg-inputbg border-b border-[rgba(114,88,89,0.5)] px-1 py-[13px] font-manrope text-base leading-[22px] text-inksoft placeholder:text-inksoft/50 focus:outline-none focus:border-ink transition">
                            @error('email')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <label for="password" class="font-manrope font-semibold text-sm leading-5 tracking-[0.7px] text-inksoft">
                                    KATA SANDI
                                </label>
                                <span class="font-manrope font-medium text-xs leading-4 text-ink">Lupa kata sandi?</span>
                            </div>
                            <div class="relative">
                                <input type="password" id="password" name="password" required
                                       placeholder="••••••••"
                                       class="w-full bg-inputbg border-b border-[rgba(114,88,89,0.5)] px-1 py-[13px] pr-8 font-manrope text-base leading-[22px] text-inksoft placeholder:text-inksoft/50 focus:outline-none focus:border-ink transition">
                                <button type="button" id="togglePassword" aria-label="Tampilkan kata sandi"
                                        class="absolute right-0 top-1/2 -translate-y-1/2 flex items-center justify-center w-6 h-6 text-inksoft hover:text-ink transition">
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

                        <!-- Remember Me -->
                        <label class="flex items-center gap-2 py-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" value="1" id="remember"
                                   class="w-4 h-4 rounded bg-cream border border-cardborder focus:ring-ink focus:outline-none accent-ink">
                            <span class="font-manrope text-base leading-6 text-inksoft">Ingat saya</span>
                        </label>

                        <!-- Submit -->
                        <button type="submit"
                                class="w-full bg-ink text-white font-manrope font-semibold text-sm leading-5 tracking-[0.7px] rounded-lg py-3 shadow-[0_1px_2px_rgba(0,0,0,0.05)] hover:bg-[#1a0001] transition">
                            Masuk
                        </button>

                        <!-- Divider -->
                        <div class="relative flex items-center justify-center py-0">
                            <div class="absolute inset-x-0 top-1/2 h-px bg-cardborder/30"></div>
                            <span class="relative px-4 bg-[#F6F3EE] font-manrope font-medium text-xs leading-4 text-inksoft">
                                atau
                            </span>
                        </div>

                        <!-- Secondary Action -->
                        <div class="flex items-center justify-center gap-1">
                            <span class="font-manrope text-base leading-6 text-center text-inksoft">Belum punya akun?</span>
                            <a href="{{ route('register') }}" class="font-manrope font-semibold text-sm leading-5 tracking-[0.7px] text-accentlink hover:text-ink transition">
                                Daftar sekarang
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Minimal Footer -->
                <div class="mt-6 flex items-center justify-center gap-6 opacity-60">
                    <span class="font-manrope font-medium text-xs leading-4 text-center text-inksoft cursor-pointer">Bantuan</span>
                    <span class="font-manrope text-base leading-6 text-inksoft">©</span>
                    <span class="font-manrope font-medium text-xs leading-4 text-center text-inksoft cursor-pointer">Ketentuan</span>
                </div>
            </div>
        </div>

        <!-- Right: Visual -->
        <div class="hidden lg:block flex-1 relative min-h-[480px] bg-cover bg-center"
             style="background-image: url('{{ asset('images/skin_scan.jpg') }}');">
            <div class="absolute inset-0 bg-gradient-to-r from-cream via-cream/40 to-transparent"></div>
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