<section class="relative w-full py-20 px-6 bg-white overflow-hidden" id="about">
    <style>
        .reveal-left { opacity: 0; transform: translateX(-40px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-right { opacity: 0; transform: translateX(40px); transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-zoom { opacity: 0; transform: scale(0.9) translateY(20px); transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1); }
        .revealed { opacity: 1; transform: translate(0) scale(1); }
    </style>

    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold tracking-widest text-[#2D4A34] uppercase px-4 py-1.5 bg-[#EAF5ED] rounded-full border border-emerald-100 inline-block">
            TENTANG TETAPKULIAH
        </span>
        <h2 class="text-3xl md:text-4xl font-bold text-[#2D4A34] leading-tight mt-4">
            Membangun Resiliensi Bersama
        </h2>
        <p class="text-[#6B7280] text-sm mt-3 max-w-md mx-auto leading-relaxed">
            Pendamping akademik untuk membantu menavigasi kehidupan perkuliahan dengan tenang.
        </p>
        <div class="flex items-center justify-center gap-2 mt-5 text-[#2D4A34]/30">
            <span class="h-px w-10 bg-[#2D4A34]/20"></span>
            <i data-lucide="layers" class="w-4 h-4 text-[#2D4A34]"></i>
            <span class="h-px w-10 bg-[#2D4A34]/20"></span>
        </div>
    </div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-4 items-center">
        <div class="space-y-12 order-2 lg:order-1 reveal-left">
            <div class="flex flex-col sm:flex-row-reverse items-center lg:items-start text-center lg:text-right gap-4">
                <div class="w-12 h-12 shrink-0 bg-[#EAF5ED] text-[#2D4A34] rounded-full flex items-center justify-center border border-emerald-100/60 shadow-sm">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-[#1F2937] text-lg">Pendekatan Empatik</h3>
                    <p class="text-sm text-[#6B7280] max-w-xs lg:ml-auto leading-relaxed">
                        Didukung oleh rekan sebaya yang memahami betul dinamika dunia kuliah.
                    </p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row-reverse items-center lg:items-start text-center lg:text-right gap-4">
                <div class="w-12 h-12 shrink-0 bg-[#EAF5ED] text-[#2D4A34] rounded-full flex items-center justify-center border border-emerald-100/60 shadow-sm">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-[#1F2937] text-lg">Aman & Terpercaya</h3>
                    <p class="text-sm text-[#6B7280] max-w-xs lg:ml-auto leading-relaxed">
                        Sistem terenkripsi untuk menjaga privasi cerita dan data pribadi mahasiswa.
                    </p>
                </div>
            </div>
        </div>
        <div class="relative flex justify-center items-center py-6 order-1 lg:order-2 reveal-zoom">
            <div class="absolute w-64 h-64 sm:w-80 sm:h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <img 
                src="{{ asset('img/landing/features.png') }}" 
                alt="Akademik Clay 3D"
                class="relative z-10 w-full max-w-[260px] sm:max-w-[320px] h-auto object-contain hover:scale-[1.03] transition-transform duration-500 mix-blend-multiply filter drop-shadow-[0_20px_35px_rgba(45,74,52,0.18)]"
            >
        </div>
        <div class="space-y-12 order-3 reveal-right">
            <div class="flex flex-col sm:flex-row items-center lg:items-start text-center lg:text-left gap-4">
                <div class="w-12 h-12 shrink-0 bg-[#EAF5ED] text-[#2D4A34] rounded-full flex items-center justify-center border border-emerald-100/60 shadow-sm">
                    <i data-lucide="presentation" class="w-6 h-6"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-[#1F2937] text-lg">Berbasis Riset</h3>
                    <p class="text-sm text-[#6B7280] max-w-xs lg:mr-auto leading-relaxed">
                        Dirancang berdasarkan penelitian resiliensi akademik terkini di Indonesia.
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center lg:items-start text-center lg:text-left gap-4">
                <div class="w-12 h-12 shrink-0 bg-[#EAF5ED] text-[#2D4A34] rounded-full flex items-center justify-center border border-emerald-100/60 shadow-sm">
                    <i data-lucide="globe" class="w-6 h-6"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-[#1F2937] text-lg">Komunitas Global</h3>
                    <p class="text-sm text-[#6B7280] max-w-xs lg:mr-auto leading-relaxed">
                        Dukungan tanpa batas untuk mempermudah jejaring karir dan akademis mahasiswa.
                    </p>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.querySelectorAll('.reveal-left, .reveal-right, .reveal-zoom').forEach(el => {
                            el.classList.add('revealed');
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            const section = document.getElementById('about');
            if (section) observer.observe(section);
        });
    </script>
</section>