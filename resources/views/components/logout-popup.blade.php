{{-- Popup konfirmasi logout (default hidden, dibuka oleh JS di topbar.blade.php) --}}
<div
    id="logout-popup"
    role="dialog"
    aria-modal="true"
    aria-labelledby="logout-popup-title"
    class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 px-4 backdrop-blur-[2px]"
>
    <div class="w-full max-w-sm rounded-2xl bg-white p-8 text-center shadow-2xl ring-1 ring-black/5">

        {{-- Ikon --}}
        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
            <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M16 17l5-5-5-5M21 12H9M13 5H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h8" />
            </svg>
        </div>

        {{-- Teks --}}
        <h2 id="logout-popup-title" class="text-xl font-bold text-[#1b1b1f]">Confirm Logout</h2>
        <p class="mt-2 text-sm leading-relaxed text-[#55555c]">
            Are you sure you want to log out? You will need to log back in to access your account
        </p>

        {{-- Tombol --}}
        <div class="mt-7 flex items-center justify-center gap-4">
            {{-- Cancel --}}
            <button
                id="cancel-logout"
                type="button"
                class="flex-1 rounded-full border border-[#dcdce1] bg-white py-2.5 text-sm font-semibold text-[#34343a] transition hover:bg-[#f7f7fa] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/20"
            >
                Cancel
            </button>

            {{-- Logout (form POST bawaan Laravel) --}}
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-full bg-red-500 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
