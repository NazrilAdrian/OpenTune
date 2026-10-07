<header class="fixed inset-x-0 top-0 z-30 lg:left-[274px]">
    <div class="flex items-center gap-4 bg-[#f7f9fc]/95 px-4 py-6 backdrop-blur sm:px-6 lg:px-10">
        <form action="{{ route('search') }}" method="GET" class="mx-auto flex w-full max-w-[560px]">
            <label class="sr-only" for="global-search">Cari lagu atau album</label>
            <div class="relative w-full">
                <input id="global-search" name="q" value="{{ request('q') }}" type="search"
                    placeholder=""
                    class="h-12 w-full rounded-xl border border-[#dfe1e7] bg-white px-5 pr-12 text-sm text-[#292a2e] outline-none transition placeholder:text-[#a2a4aa] focus:border-[#8c6af5] focus:ring-4 focus:ring-[#8c6af5]/10">
                <button type="submit" aria-label="Cari" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-lg p-2 text-[#777983] hover:bg-[#f1efff] hover:text-[#5d32eb]">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current" stroke-width="2">
                        <circle cx="11" cy="11" r="6"></circle><path d="m16 16 4 4"></path>
                    </svg>
                </button>
            </div>
        </form>

        <div class="hidden items-center gap-4 sm:flex">
            @auth
                <a href="{{ route('songs.create') }}" class="inline-flex h-11 min-w-[138px] items-center justify-center rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] px-6 text-base font-semibold text-white shadow-sm transition hover:brightness-105">
                    Upload
                </a>
                <a href="{{ route('profile.show') }}" class="block h-11 w-11 overflow-hidden rounded-full ring-2 ring-white shadow-sm">
                    <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->username }}" class="h-full w-full object-cover">
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex h-11 min-w-[138px] items-center justify-center rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] px-6 text-base font-semibold text-white shadow-sm">
                    Login
                </a>
            @endauth
        </div>
    </div>
</header>
