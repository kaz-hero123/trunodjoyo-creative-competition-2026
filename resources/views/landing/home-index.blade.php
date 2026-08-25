<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> TetapKuliah </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white font-sans text-gray-800 antialiased overflow-x-hidden">
    <nav x-data="{ 
             scrolled: false, 
             mobileOpen: false, 
             activeSection: 'home', 
             updateActive() {
                 const sections = [
                     { id: 'home', name: 'home' },
                     { id: 'about', name: 'about' },
                     { id: 'services', name: 'services' },
                     { id: 'process', name: 'process' }
                 ];
                 const scrollPosition = window.scrollY + 200;
                 let current = 'home';
                 for (const section of sections) {
                     const el = document.getElementById(section.id);
                     if (el) {
                         const top = el.offsetTop;
                         const height = el.offsetHeight;
                         if (scrollPosition >= top && scrollPosition < top + height) {
                             current = section.name;
                         }
                     }
                 }
                 this.activeSection = current;
             }
         }" 
         x-init="
             window.addEventListener('scroll', () => { 
                 scrolled = window.scrollY > 50;
                 updateActive();
             });
             updateActive();
         "
         :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm py-3 px-4 sm:px-6 md:px-12 text-gray-800' : 'bg-transparent text-white py-4 sm:py-5 px-4 sm:px-6 md:px-12'"
         class="fixed top-0 left-0 w-full z-50 transition-all duration-300">
        
        <div class="flex justify-between items-center w-full">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-9 sm:h-10 sm:w-10">
                <span :class="scrolled ? 'text-[#2D4A34]' : 'text-white'" class="text-xl sm:text-2xl font-black ml-2 transition-colors duration-300">TetapKuliah</span>
            </div>
            
            <div id="nav-links" class="hidden md:flex items-center gap-10 font-semibold text-[14px]">
                
                <a href="#home" 
                   @click="activeSection = 'home'"
                   class="relative py-1.5 transition-colors group"
                   :class="activeSection === 'home' ? (scrolled ? 'text-[#2D4A34] font-bold' : 'text-white font-bold') : (scrolled ? 'text-gray-600 hover:text-[#2D4A34]' : 'text-white/80 hover:text-white')">
                    Home
                    <span class="absolute bottom-0 left-0 h-[2.5px] w-full rounded-full transition-all duration-300 transform origin-left"
                          :class="activeSection === 'home' ? (scrolled ? 'bg-[#2D4A34] scale-x-100' : 'bg-emerald-400 scale-x-100') : 'scale-x-0 group-hover:scale-x-100 opacity-60 ' + (scrolled ? 'bg-[#2D4A34]' : 'bg-white')"></span>
                </a>

                <a href="#about" 
                   @click="activeSection = 'about'"
                   class="relative py-1.5 transition-colors group"
                   :class="activeSection === 'about' ? (scrolled ? 'text-[#2D4A34] font-bold' : 'text-white font-bold') : (scrolled ? 'text-gray-600 hover:text-[#2D4A34]' : 'text-white/80 hover:text-white')">
                    Tentang Kami
                    <span class="absolute bottom-0 left-0 h-[2.5px] w-full rounded-full transition-all duration-300 transform origin-left"
                          :class="activeSection === 'about' ? (scrolled ? 'bg-[#2D4A34] scale-x-100' : 'bg-emerald-400 scale-x-100') : 'scale-x-0 group-hover:scale-x-100 opacity-60 ' + (scrolled ? 'bg-[#2D4A34]' : 'bg-white')"></span>
                </a>

                <a href="#services" 
                   @click="activeSection = 'services'"
                   class="relative py-1.5 transition-colors group"
                   :class="activeSection === 'services' ? (scrolled ? 'text-[#2D4A34] font-bold' : 'text-white font-bold') : (scrolled ? 'text-gray-600 hover:text-[#2D4A34]' : 'text-white/80 hover:text-white')">
                    Layanan
                    <span class="absolute bottom-0 left-0 h-[2.5px] w-full rounded-full transition-all duration-300 transform origin-left"
                          :class="activeSection === 'services' ? (scrolled ? 'bg-[#2D4A34] scale-x-100' : 'bg-emerald-400 scale-x-100') : 'scale-x-0 group-hover:scale-x-100 opacity-60 ' + (scrolled ? 'bg-[#2D4A34]' : 'bg-white')"></span>
                </a>

                <a href="#process" 
                   @click="activeSection = 'process'"
                   class="relative py-1.5 transition-colors group"
                   :class="activeSection === 'process' ? (scrolled ? 'text-[#2D4A34] font-bold' : 'text-white font-bold') : (scrolled ? 'text-gray-600 hover:text-[#2D4A34]' : 'text-white/80 hover:text-white')">
                    Bantuan
                    <span class="absolute bottom-0 left-0 h-[2.5px] w-full rounded-full transition-all duration-300 transform origin-left"
                          :class="activeSection === 'process' ? (scrolled ? 'bg-[#2D4A34] scale-x-100' : 'bg-emerald-400 scale-x-100') : 'scale-x-0 group-hover:scale-x-100 opacity-60 ' + (scrolled ? 'bg-[#2D4A34]' : 'bg-white')"></span>
                </a>

            </div>
            
            <a class="hidden md:flex items-center" href="{{ route('login') }}">
                <button :class="scrolled ? 'bg-[#2D4A34] text-white hover:bg-[#1e382b]' : 'bg-white/10 text-white hover:bg-white/20'" class="py-2 px-6 rounded-xl font-semibold text-[14px] transition-all duration-300"> Masuk </button>
            </a>

            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg focus:outline-none" :class="scrolled ? 'text-gray-800' : 'text-white'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-cloak 
             class="md:hidden mt-3 bg-white rounded-2xl shadow-xl border border-gray-100 p-5 space-y-4 text-gray-800">
            
            <a @click="mobileOpen = false; activeSection = 'home'" href="#home" 
               class="flex items-center justify-between font-medium py-1 transition-colors"
               :class="activeSection === 'home' ? 'text-[#2D4A34] font-bold' : 'hover:text-[#2D4A34]'">
                <span>Home</span>
                <span x-show="activeSection === 'home'" class="w-2 h-2 rounded-full bg-[#2D4A34]"></span>
            </a>

            <a @click="mobileOpen = false; activeSection = 'about'" href="#about" 
               class="flex items-center justify-between font-medium py-1 transition-colors"
               :class="activeSection === 'about' ? 'text-[#2D4A34] font-bold' : 'hover:text-[#2D4A34]'">
                <span>Tentang Kami</span>
                <span x-show="activeSection === 'about'" class="w-2 h-2 rounded-full bg-[#2D4A34]"></span>
            </a>

            <a @click="mobileOpen = false; activeSection = 'services'" href="#services" 
               class="flex items-center justify-between font-medium py-1 transition-colors"
               :class="activeSection === 'services' ? 'text-[#2D4A34] font-bold' : 'hover:text-[#2D4A34]'">
                <span>Layanan</span>
                <span x-show="activeSection === 'services'" class="w-2 h-2 rounded-full bg-[#2D4A34]"></span>
            </a>

            <a @click="mobileOpen = false; activeSection = 'process'" href="#process" 
               class="flex items-center justify-between font-medium py-1 transition-colors"
               :class="activeSection === 'process' ? 'text-[#2D4A34] font-bold' : 'hover:text-[#2D4A34]'">
                <span>Bantuan</span>
                <span x-show="activeSection === 'process'" class="w-2 h-2 rounded-full bg-[#2D4A34]"></span>
            </a>

            <div class="pt-2 border-t border-gray-100">
                <button class="w-full bg-[#2D4A34] text-white py-2.5 rounded-xl font-semibold text-sm"> Masuk </button>
            </div>
        </div>
    </nav>    

    <div class="font-sans text-gray-800 antialiased bg-white overflow-x-hidden">
        @include('landing.components.hero')
        @include('landing.components.about')
        @include('landing.components.services')
        @include('landing.components.how-it-works-section')
        @include('landing.components.trust-section')
    </div>
    
    <footer class="w-full bg-[#F9F9F7] py-12 md:py-16 px-6 border-t border-gray-200">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
            <div>
                <div class="flex items-center gap-2 mb-4 font-bold text-xl text-[#2D4A34]">
                    TetapKuliah
                </div>
                <p class="text-gray-500 text-sm mb-6 max-w-xs leading-relaxed">
                    Pendamping resiliensi akademik untuk mahasiswa Indonesia. Navigasi tantangan kuliah dengan lebih tenang dan terarah.
                </p>
                <p class="text-gray-400 text-xs">© 2026 TetapKuliah – TCC 2026 Entry. All rights reserved.</p>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-4 md:mb-6">Tautan Cepat</h4>
                <ul class="space-y-3 md:space-y-4 text-sm text-gray-500">
                    <li><a href="#home" class="hover:text-[#2D4A34] transition-colors">Beranda</a></li>
                    <li><a href="#about" class="hover:text-[#2D4A34] transition-colors">Tentang Kami</a></li>
                    <li><a href="#services" class="hover:text-[#2D4A34] transition-colors">Layanan</a></li>
                    <li><a href="#process" class="hover:text-[#2D4A34] transition-colors">Bantuan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-4 md:mb-6">Legal</h4>
                <ul class="space-y-3 md:space-y-4 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-[#2D4A34] transition-colors">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-[#2D4A34] transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-[#2D4A34] transition-colors">Pusat Bantuan</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>