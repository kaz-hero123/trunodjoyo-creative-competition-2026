<section class="relative w-full py-20 px-6 bg-[#FAF9F5] overflow-hidden" id="services">
    <div class="absolute inset-0 opacity-[0.015] pointer-events-none mix-blend-overlay" style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E&quot;);"></div>
    
    <div class="flex items-center justify-center">
        <span class="text-[11px] font-bold tracking-widest text-[#2D4A34] uppercase px-3 py-1 inline-block mb-4">
            Layanan Kami
        </span>
    </div>
    
    <div class="max-w-7xl mx-auto relative z-10 pb-6">
        <div class="text-center max-w-2xl mx-auto mb-10 relative">
            <h2 class="text-3xl md:text-[32px] font-bold text-[#2D4A34] leading-tight mb-4 relative inline-block">
                Area Fokus <span class="italic font-serif text-emerald-700">Resiliensi</span>
                <span class="absolute -bottom-3 left-0 w-full h-3 text-emerald-600/60 pointer-events-none">
                    <svg viewBox="0 0 100 10" preserveAspectRatio="none" class="w-full h-full"><path d="M 0 5 Q 25 1 50 5 T 100 5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                </span>
            </h2>
            
            <p class="text-[#4A4A4A] text-[18px] font-normal leading-relaxed mt-2">
                Empat dimensi utama pendampingan untuk membantu kamu tetap tangguh dan fokus meraih impian di kampus.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6" 
             x-data="{
                 services: [
                     {
                         icon: 'graduation-cap',
                         title: 'Akademik',
                         description: 'Strategi manajemen waktu, teknik belajar efektif, dan pendampingan IPK tanpa bikin burnout.'
                     },
                     {
                         icon: 'wallet',
                         title: 'Finansial',
                         description: 'Akses informasi beasiswa, tips penghematan uang saku, dan manajemen dana kuliah yang bijak.'
                     },
                     {
                         icon: 'heart',
                         title: 'Motivasi',
                         description: 'Refleksi kesehatan mental, tips atasi rasa minder, serta pembangkit semangat kuliah setiap saat.'
                     },
                     {
                         icon: 'users',
                         title: 'Sosial',
                         description: 'Relasi sehat antar sesama mahasiswa, tips berorganisasi, serta jaringan pendukung di kampus.'
                     }
                 ]
             }">
            
            <template x-for="item in services" :key="item.title">
                <div class="bg-[#FCFCFA] p-8 rounded-3xl border border-[#2D4A34]/10 shadow-[0_8px_30px_rgb(45,74,52,0.02)] hover:shadow-[0_15px_40px_-5px_rgba(45,74,52,0.06)] hover:border-[#2D4A34]/20 hover:-translate-y-2 transition-all duration-500 flex flex-col justify-between group">
                    <div>
                        <div class="w-14 h-14 rounded-full bg-emerald-50 text-[#2D4A34] flex items-center justify-center mb-8 border border-emerald-100 group-hover:bg-[#2D4A34] group-hover:text-white transition-all duration-500 shadow-sm">
                            <i :data-lucide="item.icon" class="w-6 h-6"></i>
                        </div>
                        
                        <h3 class="font-bold text-[#2D4A34] text-xl mb-3" x-text="item.title"></h3>
                        <p class="text-[14px] text-[#6B7280] font-normal leading-relaxed" x-text="item.description"></p>
                    </div>
                </div>
            </template>

        </div>
    </div>
</section>