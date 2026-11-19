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
                <button type="button" data-open-upload-modal class="upload-form-action min-w-[138px] px-6 text-base">
                    Upload
                </button>

                {{-- Avatar + Dropdown --}}
                <div class="relative" id="user-menu-wrapper">
                    <button
                        id="user-menu-button"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-controls="user-dropdown"
                        class="block h-11 w-11 overflow-hidden rounded-full ring-2 ring-white shadow-sm transition hover:ring-[#8c6af5]/50 focus:outline-none focus:ring-[#8c6af5]"
                    >
                        <img src="{{ auth()->user()->profile_picture_url }}"
                             alt="{{ auth()->user()->username }}"
                             class="h-full w-full object-cover">
                    </button>

                    {{-- Dropdown --}}
                    <div
                        id="user-dropdown"
                        role="menu"
                        aria-labelledby="user-menu-button"
                        class="absolute right-0 top-[calc(100%+10px)] hidden w-56 rounded-xl border border-[#eaedf3] bg-white py-2 shadow-lg shadow-black/10 ring-1 ring-black/5"
                    >
                        {{-- Info user --}}
                        <div class="px-4 py-2.5">
                            <p class="truncate text-sm font-semibold text-[#1b1b1f]">{{ auth()->user()->username }}</p>
                            <p class="text-xs text-[#8a8795]">Standard Account</p>
                        </div>

                        <hr class="my-1 border-[#eaedf3]">

                        {{-- My Profile --}}
                        <a href="{{ route('profile.show') }}"
                           role="menuitem"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#34343a] transition hover:bg-[#f7f5ff]">
                            <svg class="h-4 w-4 text-[#6b6b75]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                            </svg>
                            My Profile
                        </a>

                        {{-- Setting --}}
                        <a href="{{ route('profile.edit') }}"
                           role="menuitem"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-[#34343a] transition hover:bg-[#f7f5ff]">
                            <svg class="h-4 w-4 text-[#6b6b75]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="3" />
                                <path d="M12 2v2m0 16v2M4.22 4.22l1.42 1.42m12.72 12.72 1.42 1.42M2 12h2m16 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                            </svg>
                            Account Setting
                        </a>

                        <hr class="my-1 border-[#eaedf3]">

                        {{-- Logout (BUKAN <a href>; trigger JavaScript lalu form POST) --}}
                        <button
                            id="logout-trigger"
                            type="button"
                            role="menuitem"
                            class="flex w-full items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-500 transition hover:bg-red-50"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M16 17l5-5-5-5M21 12H9M13 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h8" />
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="inline-flex h-11 min-w-[138px] items-center justify-center rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] px-6 text-base font-semibold text-white shadow-sm">
                    Login
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- Sertakan popup logout --}}
@include('components.logout-popup')

<script>
(function () {
    // ===== User dropdown toggle =====
    var menuBtn   = document.getElementById('user-menu-button');
    var dropdown  = document.getElementById('user-dropdown');

    if (menuBtn && dropdown) {
        menuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isHidden = dropdown.classList.toggle('hidden');
            menuBtn.setAttribute('aria-expanded', String(!isHidden));
        });

        // Tutup dropdown jika klik di luar
        document.addEventListener('click', function (e) {
            if (!dropdown.classList.contains('hidden') &&
                !dropdown.contains(e.target) &&
                e.target !== menuBtn) {
                dropdown.classList.add('hidden');
                menuBtn.setAttribute('aria-expanded', 'false');
            }
        });

        // Tutup dropdown dengan Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
                menuBtn.setAttribute('aria-expanded', 'false');
                menuBtn.focus();
            }
        });
    }

    // ===== Buka popup logout saat "Logout" di dropdown diklik =====
    var logoutTrigger = document.getElementById('logout-trigger');
    var logoutPopup   = document.getElementById('logout-popup');

    if (logoutTrigger && logoutPopup) {
        logoutTrigger.addEventListener('click', function () {
            // Tutup dropdown dulu
            if (dropdown) {
                dropdown.classList.add('hidden');
                if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
            }
            // Tampilkan popup
            logoutPopup.classList.remove('hidden');
            logoutPopup.classList.add('flex');
        });
    }

    // ===== Tutup popup logout saat Cancel diklik =====
    var cancelLogout = document.getElementById('cancel-logout');

    if (cancelLogout && logoutPopup) {
        cancelLogout.addEventListener('click', function () {
            logoutPopup.classList.add('hidden');
            logoutPopup.classList.remove('flex');
        });

        // Tutup jika klik overlay gelap di luar card
        logoutPopup.addEventListener('click', function (e) {
            if (e.target === logoutPopup) {
                logoutPopup.classList.add('hidden');
                logoutPopup.classList.remove('flex');
            }
        });

        // Tutup dengan Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && logoutPopup && !logoutPopup.classList.contains('hidden')) {
                logoutPopup.classList.add('hidden');
                logoutPopup.classList.remove('flex');
            }
        });
    }
})();
</script>
