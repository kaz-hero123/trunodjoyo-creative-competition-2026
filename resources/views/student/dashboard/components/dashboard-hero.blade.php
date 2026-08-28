@php
    $userName = auth()->check() ? auth()->user()->name : 'Budi';
    $displayName = $userName ? explode(' ', trim($userName))[0] : 'Budi';

    // Format tanggal hari ini dalam Bahasa Indonesia
    $currentDateFormatted = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY');

    $nextCheckInDateFormatted = null;
    if (isset($nextCheckInAt) && $nextCheckInAt) {
        $carbonNext = \Carbon\Carbon::parse($nextCheckInAt);
        $days = (int) ceil(now()->diffInDays($carbonNext, false));
        if ($days > 0) {
            $checkInLabel = $days . ' Hari lagi';
        } elseif ($days === 0) {
            $checkInLabel = 'Hari ini';
        } else {
            $checkInLabel = 'Siap Check-In';
        }
        $nextCheckInDateFormatted = $carbonNext->locale('id')->isoFormat('D MMM YYYY');
    } else {
        $checkInLabel = '5 Hari lagi';
        $nextCheckInDateFormatted = \Carbon\Carbon::now()->addDays(5)->locale('id')->isoFormat('D MMM YYYY');
    }
@endphp

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 py-6 px-1">
    <div class="space-y-2 max-w-xl">
        <h1 class="text-2xl sm:text-3xl md:text-[32px] font-extrabold text-gray-900 tracking-tight leading-tight">
            Halo, <span class="text-[#2D4A34]">{{ $displayName }}</span>.
        </h1>
        <p class="text-sm sm:text-base text-gray-500 font-normal leading-relaxed">
            Senang melihatmu kembali. Ruang ini aman untuk memantau perjalanan dan pertumbuhanmu.
        </p>
    </div>

    <div class="bg-[#F6F6F2] border border-gray-200/80 rounded-xl px-5 py-4 flex items-center gap-3.5 shadow-sm shrink-0">
        <div class="w-11 h-11 rounded-lg bg-[#BCE7C5] text-[#234730] flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v2m0 0v2m0-2h2m-2 0h-2" />
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="text-base sm:text-lg font-bold text-gray-900 tracking-tight mt-0.5">{{ $checkInLabel }}</span>
            @if($nextCheckInDateFormatted)
                <span class="text-[11px] text-gray-400 font-medium leading-none mt-1">({{ $nextCheckInDateFormatted }})</span>
            @endif
        </div>
    </div>
</div>
