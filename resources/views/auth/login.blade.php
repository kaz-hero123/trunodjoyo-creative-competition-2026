<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - TetapKuliah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#F0F0EB] min-h-screen flex items-center justify-center p-6 md:p-10">

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden relative"
         x-data="{ showPassword: false }">

        <div class="flex flex-col lg:flex-row h-[580px] relative">

            {{-- LEFT SIDE: FORM --}}
            <div class="flex-1 p-8 sm:p-12 flex flex-col justify-center bg-white overflow-y-auto relative z-10">
                <div class="w-full max-w-sm mx-auto">

                {{-- Brand --}}
                <div class="mb-8">
                    <a href="/" class="flex items-center gap-2 text-[#2D4A34] text-lg font-bold tracking-tight">
                        <img src="{{ asset('img/particle/login.png') }}" alt="Logo" class="h-7 w-7 object-contain rounded-md">
                        <span>TetapKuliah</span>
                    </a>
                </div>

                {{-- Heading --}}
                <div class="mb-7">
                    <h1 class="text-2xl font-bold text-gray-900 mb-1.5 tracking-tight">Masuk ke Akun</h1>
                    <p class="text-sm text-gray-400 font-normal">Lanjutkan perjalanan belajarmu hari ini.</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 text-xs px-4 py-3 rounded-xl font-medium">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-gray-600 tracking-wide">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@mahasiswa.ac.id"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2D4A34]/40 focus:border-[#2D4A34] focus:bg-white transition-all placeholder-gray-300"
                        >
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold text-gray-600 tracking-wide">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                placeholder="••••••••"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2D4A34]/40 focus:border-[#2D4A34] focus:bg-white transition-all placeholder-gray-300 pr-12"
                            >
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                                <svg x-show="!showPassword" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.07 10.07 0 014.213-5.26m3.673-1.354A9.957 9.957 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full bg-[#2D4A34] text-white text-sm font-semibold py-3.5 rounded-xl hover:bg-[#1f3525] active:scale-[0.99] transition-all shadow-sm mt-2">
                        Masuk
                    </button>
                </form>

                {{-- Register link --}}
                <p class="mt-7 text-center text-xs text-gray-400">
                    Belum punya akun?
                    <a href="{{ route('register') }}" data-transition class="text-[#2D4A34] font-bold hover:underline ml-0.5">
                        Daftar Sekarang
                    </a>
                </p>
                </div> {{-- end max-w wrapper --}}
            </div>

            {{-- RIGHT SIDE: IMAGE --}}
            <div class="w-2/5 bg-[#2D4A34] relative overflow-visible min-h-[300px] lg:min-h-0 rounded-r-2xl z-40">
                <img
                    src="{{ asset('img/particle/login.png') }}"
                    alt="Student Illustration"
                    class="absolute top-1/2 -left-10 -translate-y-1/2 w-[700px] h-auto object-contain select-none z-50"
                    draggable="false"
                >
            </div>
        {{-- Card Transition Curtain --}}
        <div id="page-curtain" style="position:absolute;inset:0;background:#2D4A34;z-index:30;transform:translateX(0);pointer-events:none;transition:transform 0.65s cubic-bezier(0.76,0,0.24,1);"></div>

    </div>

    <script>
        (function() {
            var curtain = document.getElementById('page-curtain');
            window.addEventListener('DOMContentLoaded', function() {
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        curtain.style.transform = 'translateX(100%)';
                    });
                });
            });
            document.addEventListener('click', function(e) {
                var link = e.target.closest('[data-transition]');
                if (!link) return;
                e.preventDefault();
                var href = link.href;
                curtain.style.transition = 'none';
                curtain.style.transform = 'translateX(100%)';
                curtain.offsetHeight;
                curtain.style.transition = 'transform 0.65s cubic-bezier(0.76,0,0.24,1)';
                curtain.style.transform = 'translateX(0)';
                setTimeout(function() { window.location.href = href; }, 680);
            });
        })();
    </script>

</body>
</html>
