<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil & Sesi Curhat - TetapKuliah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white min-h-screen text-gray-800 font-sans antialiased flex flex-col justify-between selection:bg-[#356545] selection:text-white">

    @include('student.components.student-navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-8 py-10 w-full flex-1 space-y-10">
        
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#356545] tracking-tight">
                Hasil Evaluasi & Ruang Aman
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-normal">
                Terima kasih sudah berbagi. Berikut gambaran kondisimu saat ini. Ingat, tidak apa-apa jika ada yang terasa berat.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
            <div class="lg:col-span-5 h-full">
                @include('student.results.components.results-peta-kondisi')
            </div>
            <div class="lg:col-span-7 space-y-6 sm:space-y-8">
                @include('student.results.components.results-ruang-curhat')
                @include('student.results.components.results-dukungan')
            </div>
        </div>

    </main>

    <footer class="w-full bg-white border-t border-gray-100 py-6 px-4 text-xs text-gray-400 mt-16">
        <div class="font-bold text-[#356545] text-sm">TetapKuliah</div>
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-4">
            <div class="text-gray-400">&copy; {{ date('Y') }} TetapKuliah. Menemani Langkah Pertumbuhanmu.</div>
        </div>

    </footer>
</body>
</html>
