<nav class="w-full bg-white border-b border-gray-100 py-3.5 px-4 sm:px-8 md:px-12 flex items-center justify-between sticky top-0 z-40">
    {{-- Brand Logo --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('dashboard') }}" class="text-[#2D4A34] text-xl font-bold tracking-tight flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-7 w-7 object-contain" onError="this.style.display='none'">
            <span>TetapKuliah</span>
        </a>
    </div>

    {{-- Center Links --}}
    <div class="hidden md:flex items-center gap-8">
        <a href="{{ route('dashboard') }}" class="relative py-1 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'text-[#2D4A34]' : 'text-gray-600 hover:text-gray-900 transition-colors' }}">
            Dashboard
            @if(request()->routeIs('dashboard'))
                <span class="absolute bottom-0 left-0 w-full h-[2.5px] bg-[#2D4A34] rounded-full"></span>
            @endif
        </a>

        @if(Route::has('workspace.notes.index'))
            <a href="{{ route('workspace.notes.index') }}" class="relative py-1 text-sm font-medium {{ request()->routeIs('workspace.*') ? 'text-[#2D4A34] font-semibold' : 'text-gray-600 hover:text-gray-900 transition-colors' }}">
                Resources
                @if(request()->routeIs('workspace.*'))
                    <span class="absolute bottom-0 left-0 w-full h-[2.5px] bg-[#2D4A34] rounded-full"></span>
                @endif
            </a>
        @endif

        {{-- User Profile / Account Dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="py-1 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors flex items-center gap-1 focus:outline-none">
                <span>Profile</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" 
                 @click.away="open = false" 
                 x-cloak
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 text-gray-700">
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-xs font-bold text-gray-900 truncate">{{ auth()->user()->name ?? 'Student' }}</p>
                    <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Right Action Button --}}
    <div class="flex items-center gap-3">
        @if(Route::has('check-in.create'))
            <a href="{{ route('check-in.create') }}" class="bg-[#2D4A34] text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-[#1f3525] transition-all shadow-xs">
                Mulai Check-In
            </a>
        @endif
    </div>
</nav>
