@php
    $dimScores = [
        'akademik' => (float) ($assessment->score_academic ?? 0),
        'finansial' => (float) ($assessment->score_financial ?? 0),
        'sosial' => (float) ($assessment->score_social ?? 0),
        'motivasi' => (float) ($assessment->score_motivational ?? 0),
    ];
    asort($dimScores);
    $lowestDimName = array_key_first($dimScores);
@endphp

<div class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-7 border border-gray-200/70 shadow-2xs">
    <div class="mb-5">
        <h3 class="text-xl font-bold text-[#356545] flex items-center gap-2 tracking-tight mb-2">
            <i data-lucide="sprout" class="w-5 h-5 text-[#356545]"></i>
            <span>Dukungan Untukmu</span>
        </h3>
        <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-normal">
            Terima kasih sudah jujur, itu langkah yang berani. Jangan memikulnya sendirian. Ada beberapa opsi yang bisa kita coba untuk meringankan beban <span class="lowercase font-semibold">{{ $lowestDimName }}</span>mu semester ini. Berikut beberapa informasi yang mungkin berguna:
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @if(isset($matches) && count($matches) > 0)
            @foreach($matches as $m)
                @php
                    $title = is_object($m) && isset($m->resource) ? $m->resource->title : ($m['title'] ?? 'Rekomendasi Bantuan');
                    $reason = is_object($m) ? ($m->reason ?? '') : ($m['reason'] ?? '');
                    $category = is_object($m) && isset($m->resource) ? ($m->resource->category ?? 'Bantuan') : 'Rekomendasi';
                @endphp
                <div class="bg-[#FAFBF9] hover:bg-[#F4F6F1] border border-gray-200/60 p-5 rounded-2xl transition-all shadow-2xs group flex flex-col justify-between cursor-pointer">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-md bg-[#D2E3FC] text-[#1A73E8]">
                                {{ $category }}
                            </span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 text-gray-400 group-hover:text-[#356545] transition-colors"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 text-xs sm:text-sm mb-1.5 group-hover:text-[#356545] transition-colors">
                            {{ $title }}
                        </h4>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ $reason }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-[#FAFBF9] hover:bg-[#F4F6F1] border border-gray-200/60 p-5 rounded-2xl transition-all shadow-2xs group flex flex-col justify-between cursor-pointer">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-md bg-[#D2E3FC] text-[#1A73E8]">
                            Beasiswa
                        </span>
                        <i data-lucide="arrow-up-right" class="w-4 h-4 text-gray-400 group-hover:text-[#356545] transition-colors"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xs sm:text-sm mb-1.5 group-hover:text-[#356545] transition-colors">
                        Beasiswa KIP Kuliah Jalur Khusus
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Pendaftaran masih buka hingga akhir bulan. Cek persyaratannya.
                    </p>
                </div>
            </div>

            <div class="bg-[#FAFBF9] hover:bg-[#F4F6F1] border border-gray-200/60 p-5 rounded-2xl transition-all shadow-2xs group flex flex-col justify-between cursor-pointer">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-md bg-[#F8C4C1] text-[#D93025]">
                            Bantuan UKT
                        </span>
                        <i data-lucide="arrow-up-right" class="w-4 h-4 text-gray-400 group-hover:text-[#356545] transition-colors"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xs sm:text-sm mb-1.5 group-hover:text-[#356545] transition-colors">
                        Pengajuan Penurunan UKT Semester Genap
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Informasi detail BEM Universitas mengenai alur penyesuaian UKT.
                    </p>
                </div>
            </div>

            <div class="sm:col-span-2 bg-[#FAFBF9] hover:bg-[#F4F6F1] border border-gray-200/60 p-5 rounded-2xl transition-all shadow-2xs group flex items-center gap-4 cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-[#EAF2EC] text-[#356545] flex items-center justify-center shrink-0">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-md bg-[#356545] text-white">
                            Konseling
                        </span>
                        <i data-lucide="arrow-up-right" class="w-4 h-4 text-gray-400 group-hover:text-[#356545] transition-colors"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-xs sm:text-sm mb-1 group-hover:text-[#356545] transition-colors">
                        Konseling Finansial Kemahasiswaan
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Jadwalkan sesi gratis dengan konselor kampus untuk mendiskusikan opsi pendanaanmu.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
