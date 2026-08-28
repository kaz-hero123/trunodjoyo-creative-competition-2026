<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TetapKuliah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-[#FAF9F5] min-h-screen font-sans text-gray-800 antialiased">
    
    {{-- Student Navbar --}}
    @include('student.components.student-navbar')

    <div class="max-w-7xl mx-auto p-4 sm:p-8 space-y-8">
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-700 text-sm p-4 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        {{-- Hero Header Section --}}
        @include('student.dashboard.components.dashboard-hero')

        {{-- Status & Radar Chart Grid Section --}}
        @include('student.dashboard.components.dashboard-status')

        {{-- Recommendation Cards Section --}}
        @include('student.dashboard.components.dashboard-recommendation')
    </div>
</body>
</html>
