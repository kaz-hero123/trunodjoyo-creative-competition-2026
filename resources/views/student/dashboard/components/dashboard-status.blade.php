@php
    $scoreAcademic = $latestAssessment ? (float) $latestAssessment->score_academic : 70;
    $scoreFinancial = $latestAssessment ? (float) $latestAssessment->score_financial : 65;
    $scoreMotivational = $latestAssessment ? (float) $latestAssessment->score_motivational : 80;
    $scoreSocial = $latestAssessment ? (float) $latestAssessment->score_social : 75;

    $totalScore = $latestAssessment ? (float) $latestAssessment->total_resilience_score : 72.5;
    if ($totalScore >= 80) {
        $statusText = 'Sangat Tangguh';
        $statusBadgeBg = 'bg-emerald-100 text-emerald-800';
    } elseif ($totalScore >= 55) {
        $statusText = 'Berkembang';
        $statusBadgeBg = 'bg-[#DCEEFA] text-[#1E5D88]';
    } else {
        $statusText = 'Perlu Perhatian';
        $statusBadgeBg = 'bg-amber-100 text-amber-800';
    }

    // Radar coordinates
    $centerX = 150;
    $centerY = 120;
    $maxR = 65;

    $pTopY = round($centerY - ($maxR * ($scoreMotivational / 100)), 1);
    $pRightX = round($centerX + ($maxR * ($scoreAcademic / 100)), 1);
    $pBottomY = round($centerY + ($maxR * ($scoreFinancial / 100)), 1);
    $pLeftX = round($centerX - ($maxR * ($scoreSocial / 100)), 1);

    $polygonPoints = "{$centerX},{$pTopY} {$pRightX},{$centerY} {$centerX},{$pBottomY} {$pLeftX},{$centerY}";

    $isLocked = false;
    if (isset($nextCheckInAt) && $nextCheckInAt) {
        $isLocked = now()->lessThan(\Carbon\Carbon::parse($nextCheckInAt));
    }
@endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    {{-- Left Column (Status + Radar Chart) --}}
    <div class="lg:col-span-8 space-y-6 flex flex-col justify-between">
        
        {{-- Status Resiliensi Card --}}
        <div class="bg-gradient-to-br from-[#EEF7FC] via-white to-[#F4F9FC] border border-sky-100/70 rounded-xl p-6 shadow-sm relative overflow-hidden">
            <h3 class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-3">
                STATUS RESILIENSI SAAT INI
            </h3>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1 {{ $statusBadgeBg }} text-xs font-bold rounded-md shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>{{ $statusText }}</span>
            </div>

            <p class="text-sm text-gray-600 font-normal leading-relaxed mt-4 max-w-xl">
                Kapasitas adaptasimu menunjukkan tren positif minggu ini. Terus pertahankan rutinitas baik yang sedang kamu bangun.
            </p>
        </div>

        {{-- Grafik Perkembangan Card --}}
        <div class="bg-white border border-gray-100 rounded-xl p-6 sm:p-7 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-xl font-bold text-gray-900 tracking-tight">Grafik Perkembangan</h3>
                @if($latestAssessment)
                    <a href="{{ route('results.show', $latestAssessment) }}" class="text-xs sm:text-sm font-semibold text-gray-500 hover:text-[#2D4A34] transition-colors flex items-center gap-1">
                        Lihat Detail
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
            </div>

            <p class="text-sm text-gray-500 font-normal leading-relaxed mb-6">
                Pantau keseimbangan 4 dimensi resiliensimu berdasarkan hasil check-in terakhir.
            </p>

            {{-- Radar Chart Container --}}
            <div class="bg-[#FAF9F5] border border-gray-200/60 rounded-lg p-6 flex items-center justify-center relative min-h-[260px]">
                <svg viewBox="0 0 300 240" class="w-full max-w-[340px] h-auto overflow-visible select-none">
                    <!-- Background Grid Axes & Rhombus -->
                    <!-- Outer Rhombus -->
                    <polygon points="150,55 215,120 150,185 85,120" fill="none" stroke="#E5E7EB" stroke-width="1.5" stroke-dasharray="3 3" />
                    <!-- Middle Rhombus -->
                    <polygon points="150,87.5 182.5,120 150,152.5 117.5,120" fill="none" stroke="#F3F4F6" stroke-width="1.5" stroke-dasharray="3 3" />
                    
                    <!-- Axis Lines -->
                    <line x1="150" y1="55" x2="150" y2="185" stroke="#E5E7EB" stroke-width="1.5" />
                    <line x1="85" y1="120" x2="215" y2="120" stroke="#E5E7EB" stroke-width="1.5" />

                    <!-- Filled Score Diamond -->
                    <polygon points="{{ $polygonPoints }}" fill="#5B5C5E" fill-opacity="0.75" stroke="#3A3B3C" stroke-width="2" stroke-linejoin="round" />

                    <!-- Score Vertices Dots -->
                    <circle cx="{{ $centerX }}" cy="{{ $pTopY }}" r="4" fill="#222" />
                    <circle cx="{{ $pRightX }}" cy="{{ $centerY }}" r="4" fill="#222" />
                    <circle cx="{{ $centerX }}" cy="{{ $pBottomY }}" r="4" fill="#222" />
                    <circle cx="{{ $pLeftX }}" cy="{{ $centerY }}" r="4" fill="#222" />

                    <!-- Axis Labels -->
                    <text x="150" y="42" text-anchor="middle" fill="#4B5563" font-size="12" font-weight="600">Mental</text>
                    <text x="228" y="124" text-anchor="start" fill="#4B5563" font-size="12" font-weight="600">Akademik</text>
                    <text x="150" y="202" text-anchor="middle" fill="#4B5563" font-size="12" font-weight="600">Fisik</text>
                    <text x="72" y="124" text-anchor="end" fill="#4B5563" font-size="12" font-weight="600">Sosial</text>
                </svg>
            </div>
        </div>

    </div>

    {{-- Right Column (Rest Notice + Check-In Locked/Ready) --}}
    <div class="lg:col-span-4 space-y-6 flex flex-col justify-between">
        
        {{-- Istirahat Sejenak Card --}}
        <div class="bg-[#2D4A34] text-white rounded-xl p-6 sm:p-7 shadow-sm relative overflow-hidden flex flex-col justify-between min-h-[220px]">
            <div>
                <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center mb-5 text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-2.5">Istirahat Sejenak</h3>
                
                <p class="text-xs sm:text-sm text-emerald-100/90 font-normal leading-relaxed">
                    Mengingat minggu ujian sudah dekat, pastikan kamu memberi jeda untuk matamu dan pikiranmu hari ini.
                </p>
            </div>
        </div>

        {{-- Check-In Lock / Ready Card --}}
        <div class="bg-[#F4F4EF] border border-gray-200/70 rounded-xl p-6 sm:p-7 text-center flex flex-col items-center justify-center min-h-[200px] flex-grow">
            @if($isLocked)
                <div class="w-10 h-10 rounded-lg bg-gray-200/60 flex items-center justify-center mb-3 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h4 class="text-base font-bold text-gray-800 mb-1.5">Check-In Terkunci</h4>

                <p class="text-xs text-gray-500 font-normal max-w-[210px] leading-relaxed">
                    Gunakan waktu ini untuk mempraktikkan rekomendasi sebelumnya.
                </p>
            @else
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-[#2D4A34] flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h4 class="text-base font-bold text-gray-800 mb-1.5">Check-In Siap!</h4>

                <p class="text-xs text-gray-500 font-normal max-w-[210px] leading-relaxed mb-4">
                    Refleksikan kondisi belajar dan kesehatan mentalmu minggu ini.
                </p>

                <a href="{{ route('check-in.create') }}" class="px-5 py-2 bg-[#2D4A34] text-white text-xs font-semibold rounded-lg hover:bg-[#1f3525] transition-colors shadow-xs">
                    Mulai Sekarang
                </a>
            @endif
        </div>

    </div>
</div>
