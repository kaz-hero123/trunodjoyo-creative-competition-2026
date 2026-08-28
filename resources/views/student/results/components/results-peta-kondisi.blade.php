@php
    $scoreAcademic = (float) ($assessment->score_academic ?? 0);
    $scoreFinancial = (float) ($assessment->score_financial ?? 0);
    $scoreSocial = (float) ($assessment->score_social ?? 0);
    $scoreMotivational = (float) ($assessment->score_motivational ?? 0);

    $getStatusConfig = function($score) {
        if ($score >= 70) {
            return [
                'label' => 'Kuat',
                'bg' => 'bg-[#F2F5F0]',
                'badge' => 'bg-[#D4E8DA] text-[#2C5E3B]'
            ];
        } elseif ($score >= 40) {
            return [
                'label' => 'Berkembang',
                'bg' => 'bg-[#F2F5F0]',
                'badge' => 'bg-[#D6E4FB] text-[#1A73E8]'
            ];
        } else {
            return [
                'label' => 'Perlu Perhatian',
                'bg' => 'bg-[#FDEBEA]',
                'badge' => 'bg-[#F8C4C1] text-[#D93025]'
            ];
        }
    };

    $statusAcademic = $getStatusConfig($scoreAcademic);
    $statusFinancial = $getStatusConfig($scoreFinancial);
    $statusSocial = $getStatusConfig($scoreSocial);
    $statusMotivational = $getStatusConfig($scoreMotivational);

    $centerX = 140;
    $centerY = 120;
    $maxR = 70;

    $pTopY = round($centerY - ($maxR * ($scoreAcademic / 100)), 1);
    $pRightX = round($centerX + ($maxR * ($scoreSocial / 100)), 1);
    $pBottomY = round($centerY + ($maxR * ($scoreFinancial / 100)), 1);
    $pLeftX = round($centerX - ($maxR * ($scoreMotivational / 100)), 1);

    $polygonPoints = "{$centerX},{$pTopY} {$pRightX},{$centerY} {$centerX},{$pBottomY} {$pLeftX},{$centerY}";
@endphp

<div class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 border border-gray-200/70 shadow-[0_4px_25px_rgba(0,0,0,0.03)] flex flex-col justify-between h-full">
    <div>
        <h2 class="text-xl font-bold text-gray-900 text-center tracking-tight mb-4">
            Peta Kondisimu
        </h2>

        <div class="flex items-center justify-center my-4 select-none">
            <svg viewBox="0 0 280 240" class="w-full max-w-[290px] h-auto overflow-visible">
                <polygon points="140,50 210,120 140,190 70,120" fill="none" stroke="#E5E7EB" stroke-width="1.2" stroke-dasharray="3 3" />
                <polygon points="140,73.3 186.6,120 140,166.6 93.3,120" fill="none" stroke="#F3F4F6" stroke-width="1.2" stroke-dasharray="3 3" />
                <polygon points="140,96.6 163.3,120 140,143.3 116.6,120" fill="none" stroke="#F3F4F6" stroke-width="1.2" stroke-dasharray="3 3" />

                <line x1="140" y1="50" x2="140" y2="190" stroke="#E5E7EB" stroke-width="1.2" />
                <line x1="70" y1="120" x2="210" y2="120" stroke="#E5E7EB" stroke-width="1.2" />

                <polygon points="{{ $polygonPoints }}" fill="#356545" fill-opacity="0.2" stroke="#356545" stroke-width="2.5" stroke-linejoin="round" />

                <circle cx="{{ $centerX }}" cy="{{ $pTopY }}" r="4" fill="#356545" />
                <circle cx="{{ $pRightX }}" cy="{{ $centerY }}" r="4" fill="#356545" />
                <circle cx="{{ $centerX }}" cy="{{ $pBottomY }}" r="4" fill="#EA580C" />
                <circle cx="{{ $pLeftX }}" cy="{{ $centerY }}" r="4" fill="#356545" />

                <text x="140" y="38" text-anchor="middle" fill="#374151" font-size="12" font-weight="600">Akademik</text>
                <text x="222" y="124" text-anchor="start" fill="#374151" font-size="12" font-weight="600">Sosial</text>
                <text x="140" y="206" text-anchor="middle" fill="#374151" font-size="12" font-weight="600">Finansial</text>
                <text x="58" y="124" text-anchor="end" fill="#374151" font-size="12" font-weight="600">Motivasi</text>
            </svg>
        </div>
    </div>

    <div class="space-y-3 mt-6">
        <div class="{{ $statusAcademic['bg'] }} rounded-2xl p-3.5 flex items-center justify-between transition-colors">
            <span class="text-xs sm:text-sm font-bold text-gray-800">Akademik</span>
            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusAcademic['badge'] }}">
                {{ $statusAcademic['label'] }}
            </span>
        </div>

        <div class="{{ $statusFinancial['bg'] }} rounded-2xl p-3.5 flex items-center justify-between transition-colors">
            <span class="text-xs sm:text-sm font-bold text-gray-800">Finansial</span>
            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusFinancial['badge'] }}">
                {{ $statusFinancial['label'] }}
            </span>
        </div>

        <div class="{{ $statusSocial['bg'] }} rounded-2xl p-3.5 flex items-center justify-between transition-colors">
            <span class="text-xs sm:text-sm font-bold text-gray-800">Sosial</span>
            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusSocial['badge'] }}">
                {{ $statusSocial['label'] }}
            </span>
        </div>

        <div class="{{ $statusMotivational['bg'] }} rounded-2xl p-3.5 flex items-center justify-between transition-colors">
            <span class="text-xs sm:text-sm font-bold text-gray-800">Motivasi</span>
            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusMotivational['badge'] }}">
                {{ $statusMotivational['label'] }}
            </span>
        </div>
    </div>
</div>
