<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi — LumiMate</title>

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
                        divider: '#E5E2DD',
                        textmain: '#1C1C19',
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
            background: linear-gradient(0deg, #FCF9F4, #FCF9F4), #FFFFFF;
            color: #2D0003;
            font-family: 'Manrope', sans-serif;
        }
        .radio-card { transition: all .15s ease; }
        .radio-card.selected {
            border-color: #2D0003 !important;
            background: #FFFFFF;
            box-shadow: 0 2px 12px rgba(45, 0, 3, 0.08);
        }
        .radio-card.selected .radio-input { background: #2D0003; border-color: #2D0003; }
        .radio-card.selected .radio-input::after { opacity: 1; }
        .fade-step { animation: fadeStep .3s ease; }
        @keyframes fadeStep {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="flex items-center justify-between px-8 sm:px-16 py-4 border-b border-divider">
        <div>
            <span class="font-garamond text-2xl font-medium text-ink leading-8">LumiMate</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs font-medium uppercase tracking-[0.075em] text-inksoft">Langkah {{ $currentStep ?? 1 }}: Analisis</span>
            <span class="w-5 h-5 rounded-sm bg-ink flex items-center justify-center">
                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
            </span>
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 text-xs font-medium uppercase tracking-[0.075em] text-inksoft hover:text-ink transition">
                <!-- Close / Back icon -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Keluar
            </a>
        </div>
    </header>

    <!-- Progress Indicator -->
    <div class="mx-auto w-full max-w-[1200px] px-4 sm:px-10 pt-8">
        <div class="relative">
            <!-- Progress tracks & bar -->
            <div class="flex justify-between items-end pb-2">
                <span class="text-xs font-medium uppercase tracking-[0.075em] text-ink" id="progressLabel">1 dari {{ $questions->count() }} Pertanyaan</span>
                <span class="text-xs font-medium text-inksoft" id="goalLabel">Analisis Kulit</span>
            </div>
            <div class="relative h-[2px] w-full bg-divider mb-8">
                <div class="absolute left-0 top-0 h-[2px] bg-ink transition-all duration-500" id="progressBar" style="width: {{ $questions->count() ? (1 / $questions->count()) * 100 : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-10 pb-12">
        <div class="w-full max-w-[1200px]">
            <form id="consultationForm" method="POST" action="{{ route('user.consultation.store') }}" class="flex flex-col lg:flex-row gap-6 lg:gap-12">
                @csrf

                <!-- Left Column: Content & Options -->
                <div class="w-full lg:w-1/2 flex flex-col">
                    @foreach ($questions as $index => $question)
                        <div class="question-step flex-1 flex flex-col {{ $index > 0 ? 'hidden' : '' }} fade-step"
                             data-step="{{ $index + 1 }}"
                             data-key="{{ $question->question_key }}"
                             data-input="{{ $question->input_type }}">

                            <!-- Heading -->
                            <h1 class="font-garamond text-4xl sm:text-[44px] leading-[1.1] font-medium text-ink tracking-[-0.02em]">
                                {{ $question->title }}
                            </h1>

                            <!-- Description -->
                            <p class="mt-4 text-lg leading-relaxed text-inksoft max-w-[512px]">
                                {{ $question->description }}
                            </p>

                            <!-- Category Label -->
                            <div class="mt-8">
                                <span class="text-xs font-medium uppercase tracking-[0.075em] text-ink">{{ $question->category_label }}</span>
                            </div>

                            <!-- Options -->
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($question->activeOptions as $optIndex => $option)
                                    @if ($question->input_type === 'multi_select')
                                        <label class="radio-card selected:border-ink cursor-pointer bg-white border border-cardborder rounded-xl p-4 flex flex-col gap-2 hover:border-inksoft transition">
                                            <span class="flex items-center justify-between">
                                                <span class="text-sm font-semibold text-textmain tracking-wide">{{ $option->label }}</span>
                                                <input type="checkbox"
                                                       name="{{ $question->question_key }}[]"
                                                       value="{{ $option->value }}"
                                                       class="radio-input appearance-none w-[18px] h-[18px] border-2 border-textmain rounded-full relative cursor-pointer shrink-0"
                                                       style="border-color:#1C1C19">
                                            </span>
                                            <span class="text-sm text-inksoft">{{ $option->description }}</span>
                                        </label>
                                    @else
                                        <label class="radio-card selected:border-ink cursor-pointer bg-white border border-cardborder rounded-xl p-4 flex flex-col gap-2 hover:border-inksoft transition">
                                            <span class="flex items-center justify-between">
                                                <span class="text-sm font-semibold text-textmain tracking-wide">{{ $option->label }}</span>
                                                <input type="radio"
                                                       name="{{ $question->question_key }}"
                                                       value="{{ $option->value }}"
                                                       class="radio-input appearance-none w-[18px] h-[18px] border-2 border-textmain rounded-full relative cursor-pointer shrink-0"
                                                       style="border-color:#1C1C19">
                                            </span>
                                            <span class="text-sm text-inksoft">{{ $option->description }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Navigation -->
                    <div class="mt-10 flex items-center justify-between">
                        <button type="button" id="prevBtn"
                                class="inline-flex items-center gap-2 text-sm font-semibold text-inksoft hover:text-ink transition py-3 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Sebelumnya
                        </button>
                        <span class="flex-1"></span>
                        <button type="button" id="nextBtn"
                                class="inline-flex items-center gap-3 px-8 py-3 rounded-full bg-ink text-white text-sm font-semibold tracking-wide hover:bg-[#1a0001] transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <span id="nextLabel">Selanjutnya</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right Column: Image -->
                <div class="w-full lg:w-1/2 flex items-start justify-end">
                    <div class="relative w-full h-[320px] sm:h-[440px] lg:h-[560px] rounded-2xl overflow-hidden shadow-[0_10px_40px_rgba(77,14,18,0.09)] bg-[#F3ECE6]"
                         style="min-height: 320px;">
                        @foreach ($questions as $qIndex => $question)
                            <img src="{{ $question->image_path ? asset($question->image_path) : asset('images/consultation/placeholder.svg') }}"
                                 alt="{{ $question->title }}"
                                 data-step="{{ $qIndex + 1 }}"
                                 class="step-image absolute inset-0 w-full h-full object-cover transition-opacity duration-300 {{ $qIndex > 0 ? 'opacity-0' : 'opacity-100' }}">
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        const steps = document.querySelectorAll('.question-step');
        const images = document.querySelectorAll('.step-image');
        const total = steps.length;
        let current = 0;

        const progressBar = document.getElementById('progressBar');
        const progressLabel = document.getElementById('progressLabel');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');

        function render() {
            steps.forEach((step, i) => {
                step.classList.toggle('hidden', i !== current);
            });
            images.forEach((img, i) => {
                img.classList.toggle('opacity-0', i !== current);
                img.classList.toggle('opacity-100', i === current);
            });

            const pct = ((current + 1) / total) * 100;
            progressBar.style.width = pct + '%';
            progressLabel.textContent = (current + 1) + ' dari ' + total + ' Pertanyaan';

            prevBtn.classList.toggle('hidden', current === 0);
            document.getElementById('nextLabel').textContent = (current === total - 1) ? 'Selesai' : 'Selanjutnya';
        }

        function isStepValid(index) {
            const inputType = steps[index].dataset.input;
            if (inputType === 'multi_select') {
                return steps[index].querySelectorAll('input:checked').length > 0;
            }
            return !!steps[index].querySelector('input:checked');
        }

        function validateCurrent() {
            const valid = isStepValid(current);
            nextBtn.disabled = !valid;
            return valid;
        }

        steps.forEach((step, i) => {
            const inputs = step.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    const card = input.closest('.radio-card');
                    if (input.type === 'radio') {
                        const siblings = card.parentElement.querySelectorAll('.radio-card');
                        siblings.forEach(s => {
                            s.classList.remove('selected');
                            const r = s.querySelector('input');
                            if (r) r.classList.remove('selected');
                        });
                        card.classList.add('selected');
                        input.classList.add('selected');
                    } else {
                        card.classList.toggle('selected', input.checked);
                        input.classList.toggle('selected', input.checked);
                    }
                    validateCurrent();
                });
            });
        });

        nextBtn.addEventListener('click', () => {
            if (total === 0) return;
            if (current === total - 1) {
                document.getElementById('consultationForm').submit();
                return;
            }
            if (!validateCurrent()) return;
            current++;
            render();
        });

        prevBtn.addEventListener('click', () => {
            if (current > 0) {
                current--;
                render();
            }
        });

        render();
    </script>
</body>
</html>
