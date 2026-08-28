<section id="home" class="relative w-full h-[90vh] min-h-[600px] flex items-center justify-center overflow-hidden">
    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
        <source src="{{ asset('img/particle/kacaw.mp4') }}" type="video/mp4">
    </video>
    <div class="absolute inset-0 bg-[#17331F]/30 mix-blend-multiply"></div> 
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#17331F]/10 to-white"></div>
    
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto flex flex-col items-center">

        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6 mt-5 md:mt-0 tracking-tight">
            Pahami Posisimu, <br class="hidden md:block">
            <span class="italic font-serif text-emerald-300">Temukan</span> Langkahmu.
        </h1>
        
        <p class="text-base md:text-lg text-gray-200/90 mb-10 max-w-2xl font-normal leading-relaxed">
            Pendamping resiliensi akademik untuk mahasiswa Indonesia. Navigasi tantangan kuliah, finansial, motivasi, dan sosial dengan lebih terarah.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="w-full sm:w-auto px-8 py-3.5 bg-[#2E503F] text-white font-bold rounded-full hover:bg-emerald-800 hover:shadow-lg hover:shadow-emerald-950/20 transform hover:-translate-y-0.5 transition-all duration-300">
                Mulai Check-In Sekarang
            </a>
            
            <a href="#about" class="w-full sm:w-auto px-8 py-3.5 bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold rounded-full hover:bg-white/20 transition-all duration-300">
                Pelajari Lebih Lanjut
            </a>
        </div>

        <p class="text-[10px] text-gray-300/60 mt-12 tracking-wide uppercase">
            Mendukung SDG 4 - Quality Education & Sustainable Communities
        </p>
    </div>
</section>
