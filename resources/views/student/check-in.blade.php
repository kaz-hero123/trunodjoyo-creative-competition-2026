@php
    $flatQuestions = [];
    if (isset($questionsByDimension) && is_array($questionsByDimension)) {
        foreach ($questionsByDimension as $dim => $qs) {
            foreach ($qs as $key => $q) {
                $flatQuestions[] = [
                    'key' => $key,
                    'dimension' => $dim,
                    'statement' => $q['statement'],
                ];
            }
        }
    }
    $oldAnswers = old('answers', []);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In Wizard - TetapKuliah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-[#FAF9F5] min-h-screen font-sans text-gray-800 antialiased flex flex-col justify-between selection:bg-[#356545] selection:text-white relative overflow-x-hidden">

    <div x-data="{
        currentStep: 0,
        hasError: false,
        questions: {{ json_encode($flatQuestions) }},
        answers: {
            @foreach($flatQuestions as $q)
                '{{ $q['key'] }}': {{ isset($oldAnswers[$q['key']]) && $oldAnswers[$q['key']] !== '' ? (int)$oldAnswers[$q['key']] : 'null' }},
            @endforeach
        },
        options: [
            { value: 1, label: 'Sangat Tidak Setuju' },
            { value: 2, label: 'Tidak Setuju' },
            { value: 3, label: 'Netral' },
            { value: 4, label: 'Setuju' },
            { value: 5, label: 'Sangat Setuju' }
        ],
        nextStep() {
            let currentKey = this.questions[this.currentStep]?.key;
            if (this.answers[currentKey] === null) {
                this.hasError = true;
                return;
            }
            this.hasError = false;
            if (this.currentStep < this.questions.length - 1) {
                this.currentStep++;
            }
        },
        prevStep() {
            this.hasError = false;
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },
        selectOption(val) {
            if (this.questions.length > 0) {
                let key = this.questions[this.currentStep].key;
                this.answers[key] = val;
                this.hasError = false;
            }
        },
        validateAndSubmit(e) {
            let firstNullIndex = this.questions.findIndex(q => this.answers[q.key] === null);
            if (firstNullIndex !== -1) {
                e.preventDefault();
                this.currentStep = firstNullIndex;
                this.hasError = true;
                return false;
            }
            this.hasError = false;
            return true;
        }
    }" 
    x-init="
        $nextTick(() => {
            if (window.createIcons) window.createIcons({ icons: window.lucideIcons });
            let firstNullIndex = questions.findIndex(q => answers[q.key] === null);
            let hasAnyAnswered = questions.some(q => answers[q.key] !== null);
            if (hasAnyAnswered && firstNullIndex !== -1) {
                currentStep = firstNullIndex;
            }
        });
        $watch('currentStep', () => {
            hasError = false;
            $nextTick(() => { if (window.createIcons) window.createIcons({ icons: window.lucideIcons }); });
        });
    "
    class="relative z-10 flex-1 flex flex-col justify-between px-4 sm:px-8 md:px-10 py-4 sm:py-6">

        <header class="w-full flex items-center justify-between mb-4 sm:mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 sm:gap-1.5 text-xs sm:text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors py-1">
                <i data-lucide="x" class="w-4 h-4 text-gray-600"></i>
                <span class="text-xs sm:text-md font-medium text-gray-700 hover:text-gray-900">Tutup</span>
            </a>

            <div class="flex items-center justify-center">
                <span class="text-lg sm:text-2xl font-bold text-[#356545] tracking-tight">TetapKuliah</span>
            </div>

            <div class="w-12 sm:w-16"></div>
        </header>

        <main class="w-full max-w-3xl mx-auto my-auto flex-1 flex flex-col justify-center">

            <div class="mb-3 sm:mb-6">
                <div class="flex items-end justify-between mb-1.5 sm:mb-2">
                    <div>
                        <span class="block text-[10px] sm:text-xs font-semibold text-gray-400 mb-0.5">Kategori</span>
                        <h3 class="text-xs sm:text-base font-bold text-[#356545]" x-text="questions[currentStep]?.dimension || 'Akademik'">Akademik</h3>
                    </div>
                    <div>
                        <span class="text-[11px] sm:text-sm font-medium text-gray-500">
                            Langkah <span x-text="currentStep + 1" class="font-bold text-gray-700">1</span> dari <span x-text="questions.length">12</span>
                        </span>
                    </div>
                </div>

                <div class="w-full h-2 sm:h-5 bg-[#E5EBE3] rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-[#356545]/70 rounded-full transition-all duration-300 ease-out"
                         :style="'width: ' + (((currentStep + 1) / (questions.length || 1)) * 100) + '%'"></div>
                </div>
            </div>

            <form method="POST" action="/check-in" id="checkin-form" @submit="validateAndSubmit($event)" class="w-full">
                @csrf

                @foreach($flatQuestions as $q)
                    <input type="hidden" name="answers[{{ $q['key'] }}]" :value="answers['{{ $q['key'] }}'] ?? ''">
                @endforeach

                <div class="bg-white/95 backdrop-blur-sm rounded-2xl sm:rounded-[32px] p-4 sm:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-[#EBEFE8]/80 transition-all duration-200">

                    <div class="min-h-[75px] sm:min-h-[130px] flex items-center justify-center mb-4 sm:mb-10 px-1 sm:px-2">
                        <h2 class="text-base sm:text-2xl md:text-[26px] font-bold text-gray-900 leading-snug sm:leading-snug text-center max-w-xl mx-auto tracking-tight"
                            x-text="questions[currentStep]?.statement">
                        </h2>
                    </div>

                    <div class="grid grid-cols-5 gap-1.5 sm:gap-4 mb-6 sm:mb-12">
                        <template x-for="opt in options" :key="opt.value">
                            <button type="button"
                                    @click="selectOption(opt.value)"
                                    :class="answers[questions[currentStep]?.key] === opt.value
                                        ? 'bg-[#356545] text-white shadow-lg shadow-[#356545]/20 scale-[1.02] sm:scale-[1.03] border-2 border-transparent'
                                        : (hasError
                                            ? 'bg-[#F4F6F2] text-gray-800 border-2 border-red-500 ring-2 ring-red-500/30'
                                            : 'bg-[#F4F6F2] hover:bg-[#EAEFE8] text-gray-800 border border-transparent')"
                                    class="flex flex-col items-center justify-center py-3 px-1 sm:py-6 sm:px-4 rounded-xl sm:rounded-2xl cursor-pointer transition-all duration-200 select-none group min-h-[85px] sm:min-h-[125px] focus:outline-none">
                                
                                <span class="text-xl sm:text-3xl font-bold mb-1 sm:mb-3 transition-transform group-hover:scale-110"
                                      :class="answers[questions[currentStep]?.key] === opt.value ? 'text-white' : 'text-gray-900'"
                                      x-text="opt.value">
                                </span>

                                <span class="text-[9px] sm:text-xs font-medium text-center leading-[1.15] sm:leading-tight transition-colors max-w-[85px]"
                                      :class="answers[questions[currentStep]?.key] === opt.value ? 'text-white/95' : 'text-gray-500'"
                                      x-text="opt.label">
                                </span>
                            </button>
                        </template>
                    </div>

                    <div class="flex items-center justify-between pt-3 sm:pt-6 border-t border-gray-100/90">
                        <div>
                            <button type="button"
                                    @click="prevStep()"
                                    x-show="currentStep > 0"
                                    class="inline-flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors py-2 px-1 cursor-pointer">
                                <i data-lucide="arrow-left" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                                <span>Sebelumnya</span>
                            </button>
                        </div>

                        <div>
                            <button type="button"
                                    x-show="currentStep < questions.length - 1"
                                    @click="nextStep()"
                                    class="inline-flex items-center gap-1.5 sm:gap-2 px-5 sm:px-7 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-semibold bg-[#356545] text-white hover:bg-[#2A5237] active:scale-[0.98] shadow-md shadow-[#356545]/20 transition-all cursor-pointer">
                                <span>Selanjutnya</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                            </button>

                            <button type="submit"
                                    x-show="currentStep === questions.length - 1"
                                    class="inline-flex items-center gap-1.5 sm:gap-2 px-5 sm:px-7 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-semibold bg-[#356545] text-white hover:bg-[#2A5237] active:scale-[0.98] shadow-md shadow-[#356545]/20 transition-all cursor-pointer">
                                <span>Selesaikan & Kirim</span>
                                <i data-lucide="check" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>

        <footer class="w-full text-center py-3 sm:py-4 text-[11px] sm:text-xs text-gray-400">
            &copy; {{ date('Y') }} TetapKuliah. All rights reserved.
        </footer>
    </div>
</body>
</html>
