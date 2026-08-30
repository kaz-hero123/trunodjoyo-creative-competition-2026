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

        <div class="bg-[#F4F4EF] border border-gray-200/70 rounded-xl p-5 sm:p-6 flex flex-col justify-between min-h-[240px] flex-grow shadow-xs">
            <div>
                <div class="flex items-center justify-between mb-4 border-b border-gray-200/80 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#2D4A34]/10 text-[#2D4A34] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 tracking-tight">Riwayat Check-In</h4>
                            <p class="text-[11px] text-gray-500">Hasil evaluasi berkala</p>
                        </div>
                    </div>

                    @if(isset($assessmentHistory) && $assessmentHistory->count() > 0)
                        <span class="px-2.5 py-0.5 text-[11px] font-semibold bg-emerald-100 text-[#2D4A34] rounded-full">
                            {{ $assessmentHistory->count() }} Sesi
                        </span>
                    @endif
                </div>

                @if(isset($assessmentHistory) && $assessmentHistory->count() > 0)
                    <div class="space-y-2.5 max-h-[220px] overflow-y-auto pr-1">
                        @foreach($assessmentHistory as $item)
                            <a href="{{ route('results.show', $item) }}" 
                               class="group flex items-center justify-between p-3 rounded-lg bg-white border border-gray-200/60 hover:border-[#2D4A34]/40 hover:shadow-xs transition-all duration-150">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-md bg-[#FAF9F5] group-hover:bg-[#2D4A34]/10 text-gray-500 group-hover:text-[#2D4A34] flex items-center justify-center transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-gray-800 group-hover:text-[#2D4A34] transition-colors">
                                                {{ $item->created_at->format('d M Y') }}
                                            </span>
                                            @if($item->is_baseline)
                                                <span class="text-[10px] px-1.5 py-0.2 bg-sky-100 text-sky-800 rounded font-medium">Baseline</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-gray-500">
                                            Skor Resiliensi: <span class="font-semibold text-gray-700">{{ number_format($item->total_resilience_score, 0) }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center text-xs font-semibold text-[#2D4A34] gap-1 group-hover:translate-x-0.5 transition-transform">
                                    <span>Lihat</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-6 text-center">
                        <div class="w-10 h-10 rounded-lg bg-gray-200/60 flex items-center justify-center mx-auto mb-2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-xs text-gray-500 font-normal leading-relaxed">
                            Belum ada riwayat check-in.
                        </p>
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-3 border-t border-gray-200/80">
                @if($isLocked)
                    <div class="flex items-center justify-between text-xs text-gray-500 bg-white/70 px-3 py-2 rounded-lg border border-gray-200/50">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Check-In Berikutnya</span>
                        </span>
                        <span class="font-bold text-gray-700">
                            {{ \Carbon\Carbon::parse($nextCheckInAt)->format('d M Y') }}
                        </span>
                    </div>
                @else
                    <a href="{{ route('check-in.create') }}" 
                       class="w-full py-2 px-4 bg-[#2D4A34] text-white text-xs font-semibold rounded-lg hover:bg-[#1f3525] transition-colors flex items-center justify-center gap-1.5 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Mulai Check-In Baru</span>
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
