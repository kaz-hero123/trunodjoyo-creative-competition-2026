<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registrasi</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#FAF9F5] min-h-screen flex items-center justify-center p-4 md:p-4 overflow-x-hidden">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden relative"
         data-aos="zoom-in" data-aos-duration="1000"
         x-data="{ showPassword: false, form: { name: '', email: '', password: '' } }">

        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[600px] relative">
            
            <div class="lg:col-span-7 relative bg-cover bg-center flex flex-col justify-between p-8 md:p-12 text-white overflow-hidden"
                style="background-image: url('{{ asset('img/auth/img1.png') }}');"
                 data-aos="fade-right" data-aos-duration="1200">
                
                <div class="absolute inset-0 bg-black/40 backdrop-brightness-90"></div>

                <div class="relative z-10">
                    <h3 class="font-serif text-2xl tracking-wide font-medium">TetapKuliah</h3>
                    <p class="text-xs tracking-widest uppercase text-emerald-100/80 mt-1">Refleksi Diri & Pencocokan Resource Akademik</p>
                </div>

                <div class="relative z-10 my-auto py-12">
                    <h1 class="font-serif text-5xl md:text-7xl font-bold tracking-tight leading-none mb-3">SIGN<br>UP</h1>
                    <p class="text-sm text-emerald-100/90 font-light">Buat akun baru untuk memulai perjalananmu.</p>
                </div>

                <div class="relative z-10 flex items-center justify-between">
                    <a href="{{ route('login') }}" class="group flex items-center space-x-2 text-xs tracking-widest uppercase font-semibold text-white hover:text-emerald-300 transition-colors">
                        <span>Sign In</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 bg-[#1B3022] p-8 md:p-10 flex flex-col justify-center relative text-white"
                 data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                
                <p class="text-xs text-emerald-200/70 mb-8">Buat akun baru untuk memulai perjalananmu.</p>

                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="bg-red-500/20 border border-red-500/50 text-red-200 text-xs p-3.5 rounded-2xl">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold tracking-widest uppercase text-emerald-200/80">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white text-zinc-800 text-sm px-4 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 shadow-inner">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold tracking-widest uppercase text-emerald-200/80">E-Mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white text-zinc-800 text-sm px-4 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 shadow-inner">
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold tracking-widest uppercase text-emerald-200/80">Semester</label>
                            <input type="number" name="semester" min="1" max="14" value="{{ old('semester', 1) }}" required class="w-full bg-white text-zinc-800 text-sm px-3 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 shadow-inner text-center">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold tracking-widest uppercase text-emerald-200/80">Fakultas</label>
                            <input type="text" name="faculty" value="{{ old('faculty', 'Teknik') }}" required class="w-full bg-white text-zinc-800 text-sm px-3 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 shadow-inner">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold tracking-widest uppercase text-emerald-200/80">Prodi / Major</label>
                            <input type="text" name="major" value="{{ old('major', 'Informatika') }}" required class="w-full bg-white text-zinc-800 text-sm px-3 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 shadow-inner">
                        </div>
                    </div>

                    <div class="space-y-1.5 relative">
                        <label class="block text-[10px] font-bold tracking-widest uppercase text-emerald-200/80">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required class="w-full bg-white text-zinc-800 text-sm px-4 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 shadow-inner pr-12">
                            
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-800 focus:outline-none">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.07 10.07 0 014.213-5.26m3.673-1.354A9.957 9.957 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                        <button type="submit" class="bg-white text-[#1B3022] font-semibold text-xs tracking-wider uppercase px-8 py-3.5 rounded-full hover:bg-emerald-100 transition-all shadow-md active:scale-95">
                            Sign Up
                        </button>

                        <a href="{{ route('login') }}" class="text-[11px] text-emerald-200/60 hover:text-white underline transition-colors">
                            I'm already member
                        </a>
                    </div>

                </form>

            </div>

        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
        });
    </script>
</body>
</html>