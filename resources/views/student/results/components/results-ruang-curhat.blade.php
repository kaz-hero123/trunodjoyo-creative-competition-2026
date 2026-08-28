@php
    $dimScores = [
        'akademik' => (float) ($assessment->score_academic ?? 0),
        'finansial' => (float) ($assessment->score_financial ?? 0),
        'sosial' => (float) ($assessment->score_social ?? 0),
        'motivasi' => (float) ($assessment->score_motivational ?? 0),
    ];
    asort($dimScores);
    $lowestDimName = array_key_first($dimScores);
@endphp

<div class="bg-[#F4F6F1] rounded-[28px] sm:rounded-[32px] p-6 sm:p-7 border border-[#E3E9DF] shadow-2xs">
    <div class="flex items-start gap-3.5 mb-5">
        <div class="w-9 h-9 rounded-full bg-[#356545] flex items-center justify-center shrink-0 text-white mt-0.5 shadow-2xs">
            <i data-lucide="help-circle" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">
                Kondisi <span class="lowercase">{{ $lowestDimName }}</span>mu sedang berat ya? Kami mengerti kekhawatiranmu. Ceritakan lebih lanjut masalahmu di sini agar kami bisa mencarikan bantuan yang tepat...
            </p>
        </div>
    </div>

    @if(isset($chatHistory) && count($chatHistory) > 0)
        <div class="mb-4 space-y-3 max-h-[220px] overflow-y-auto pr-1">
            @foreach($chatHistory as $chat)
                @if(($chat['role'] ?? '') === 'user')
                    <div class="flex justify-end">
                        <div class="bg-[#356545] text-white px-4 py-2.5 rounded-2xl rounded-tr-none text-xs sm:text-sm max-w-[85%] shadow-2xs">
                            {{ $chat['message'] ?? '' }}
                        </div>
                    </div>
                @else
                    <div class="flex justify-start items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#356545] text-white flex items-center justify-center shrink-0 text-[10px] font-bold mt-0.5">
                            AI
                        </div>
                        <div class="bg-white text-gray-800 border border-gray-200/70 px-4 py-2.5 rounded-2xl rounded-tl-none text-xs sm:text-sm max-w-[85%] shadow-2xs leading-relaxed">
                            {{ $chat['message'] ?? '' }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    <form method="POST" action="/results/{{ $assessment->id }}/chat" class="w-full overflow-hidden">
        @csrf
        
        <textarea name="message" 
                  rows="3" 
                  required 
                  placeholder="Contoh: Saya kesulitan membayar UKT semester ini karena usaha orang tua sedang sepi..." 
                  class="w-full bg-white rounded-2xl border border-gray-200/80 p-4 text-xs sm:text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#356545]/20 focus:border-[#356545] resize-none shadow-2xs transition-all mb-3"></textarea>

        <div class="flex justify-end">
            <button type="submit" 
                    class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full text-xs sm:text-sm font-semibold bg-[#356545] text-white hover:bg-[#2A5237] active:scale-[0.98] shadow-md shadow-[#356545]/20 transition-all cursor-pointer">
                <span>Kirim Cerita</span>
                <i data-lucide="send" class="w-3.5 h-3.5"></i>
            </button>
        </div>
    </form>
</div>
