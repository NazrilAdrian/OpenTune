<aside class="fixed inset-y-0 left-0 z-40 hidden w-[274px] border-r border-[#e2e4eb] bg-white lg:block">
    <div class="flex h-full flex-col px-5 py-8">
        <a href="{{ route('home') }}" class="mb-10 flex items-center gap-2.5 px-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#c89aff] to-[#5b2ff0] text-white shadow-sm">
                <svg viewBox="0 0 24 24" class="h-6 w-6 fill-none stroke-current" stroke-width="1.8">
                    <path d="M8 17.5a2.5 2.5 0 1 1-2-2.45V8.8L18 6v8.7a2.5 2.5 0 1 1-2-2.45V9L8 10.5v7Z"/>
                    <path d="M5 8a7 7 0 0 1 0 8M19 8a7 7 0 0 0 0 8"/>
                </svg>
            </span>
            <span class="text-[25px] font-bold tracking-[-0.04em] text-[#5b37ea]">OpenTune</span>
        </a>

        <nav class="space-y-4">
            <a href="{{ route('home') }}"
               class="flex h-11 items-center justify-center rounded-xl border text-base font-semibold transition {{ request()->routeIs('home') ? 'border-transparent bg-gradient-to-r from-[#d09cff] to-[#5d32eb] text-white shadow-sm' : 'border-[#e1e3e9] bg-white text-[#34343a] hover:bg-[#faf9ff]' }}">
                Home
            </a>
            @auth
                <a href="{{ route('songs.mine') }}"
                   class="flex h-11 items-center justify-center rounded-xl border text-base font-semibold transition {{ request()->routeIs('songs.mine') ? 'border-transparent bg-gradient-to-r from-[#d09cff] to-[#5d32eb] text-white shadow-sm' : 'border-[#e1e3e9] bg-white text-[#34343a] hover:bg-[#faf9ff]' }}">
                    Your Music
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="flex h-11 items-center justify-center rounded-xl border border-[#e1e3e9] bg-white text-base font-semibold text-[#34343a] hover:bg-[#faf9ff]">
                    Your Music
                </a>
            @endauth
        </nav>

        <div class="mt-4 flex-1 rounded-xl border border-[#e1e3e9] bg-white"></div>
    </div>
</aside>
