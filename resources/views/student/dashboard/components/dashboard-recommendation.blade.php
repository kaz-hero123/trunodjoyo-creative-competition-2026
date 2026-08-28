<div class="space-y-6 pt-4">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                Rekomendasi Terpilih Untukmu
            </h2>
            <p class="text-sm text-gray-500 font-normal mt-1">
                Materi yang relevan dengan fokus adaptasimu minggu ini.
            </p>
        </div>

        <a href="#" class="text-sm font-semibold text-[#2D4A34] hover:text-[#1f3525] transition-colors inline-flex items-center gap-1 shrink-0">
            <span>Lihat Semua</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Card 1: Artikel --}}
        <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
            <div>
                {{-- Image Container --}}
                <div class="relative w-full h-48 bg-[#EAF3EC] overflow-hidden flex items-center justify-center">
                    <img 
                        src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&auto=format&fit=crop&q=80" 
                        alt="Teknik Manajemen Waktu"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
                    >
                    <div class="absolute top-4 left-4 z-10">
                        <span class="bg-white/90 backdrop-blur-xs text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-md shadow-xs">
                            Artikel
                        </span>
                    </div>
                </div>

                {{-- Content Container --}}
                <div class="p-5 sm:p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-md">
                            AKADEMIK
                        </span>
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-md">
                            FOKUS
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 leading-snug group-hover:text-[#2D4A34] transition-colors line-clamp-2">
                        Teknik Manajemen Waktu Saat Beban Tugas Menumpuk
                    </h3>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-5 sm:px-6 pb-5 flex items-center justify-between text-xs text-gray-400 font-medium">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>5 Menit</span>
                </div>

                <button type="button" class="text-gray-400 hover:text-[#2D4A34] transition-colors p-1" title="Simpan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Card 2: Komunitas --}}
        <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
            <div>
                {{-- Image Container --}}
                <div class="relative w-full h-48 bg-[#E3F2FD] overflow-hidden flex items-center justify-center">
                    <img 
                        src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&auto=format&fit=crop&q=80" 
                        alt="Mengatasi Homesick"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
                    >
                    <div class="absolute top-4 left-4 z-10">
                        <span class="bg-[#D6EEFE] text-[#1E5D88] text-xs font-semibold px-2.5 py-1 rounded-md shadow-xs">
                            Komunitas
                        </span>
                    </div>
                </div>

                {{-- Content Container --}}
                <div class="p-5 sm:p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-md">
                            SOSIAL
                        </span>
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-md">
                            ADAPTASI
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 leading-snug group-hover:text-[#2D4A34] transition-colors line-clamp-2">
                        Sesi Berbagi: Mengatasi Homesick di Bulan Pertama
                    </h3>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-5 sm:px-6 pb-5 flex items-center justify-between text-xs text-gray-400 font-medium">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Besok, 15:00</span>
                </div>

                <button type="button" class="text-gray-400 hover:text-[#2D4A34] transition-colors p-1" title="Simpan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Card 3: Latihan --}}
        <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
            <div>
                {{-- Image Container --}}
                <div class="relative w-full h-48 bg-[#FCE4EC] overflow-hidden flex items-center justify-center">
                    <img 
                        src="https://images.unsplash.com/photo-1517842645767-c639042777db?w=600&auto=format&fit=crop&q=80" 
                        alt="Jurnal Syukur Mingguan"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
                    >
                    <div class="absolute top-4 left-4 z-10">
                        <span class="bg-[#FCE4EC] text-[#C2185B] text-xs font-semibold px-2.5 py-1 rounded-md shadow-xs">
                            Latihan
                        </span>
                    </div>
                </div>

                {{-- Content Container --}}
                <div class="p-5 sm:p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-md">
                            MENTAL
                        </span>
                        <span class="text-[10px] font-bold tracking-wider uppercase text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-md">
                            EMOSI
                        </span>
                    </div>

                    <h3 class="text-base font-bold text-gray-900 leading-snug group-hover:text-[#2D4A34] transition-colors line-clamp-2">
                        Jurnal Syukur Mingguan: Menemukan Hal Kecil
                    </h3>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-5 sm:px-6 pb-5 flex items-center justify-between text-xs text-gray-400 font-medium">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span>Interaktif</span>
                </div>

                <button type="button" class="text-gray-400 hover:text-[#2D4A34] transition-colors p-1" title="Simpan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>
