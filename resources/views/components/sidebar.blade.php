@php
    // Ambil playlist milik user yang sedang login
    $sidebarPlaylists = auth()->check()
        ? auth()->user()->playlists()->oldest()->get(['id', 'name'])
        : collect();

    // Cek apakah modal perlu dibuka kembali setelah validasi gagal
    $reopenPlaylistModal = old('_from') === 'sidebar-playlist'
        && $errors->has('name');
@endphp


<!-- ==================== SIDEBAR ==================== -->
<aside class="fixed inset-y-0 left-0 z-40 hidden w-[274px] border-r border-[#e2e4eb] bg-white lg:block">

    <div class="flex h-full flex-col px-5 py-8">

        <!-- Logo -->
        <a href="{{ route('home') }}"
           class="mb-10 flex items-center gap-2.5 px-3">

            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#c89aff] to-[#5b2ff0] text-white">

                <svg viewBox="0 0 24 24"
                     class="h-6 w-6 fill-none stroke-current"
                     stroke-width="1.8">

                    <path d="M8 17.5a2.5 2.5 0 1 1-2-2.45V8.8L18 6v8.7a2.5 2.5 0 1 1-2-2.45V9L8 10.5v7Z"/>
                    <path d="M5 8a7 7 0 0 1 0 8M19 8a7 7 0 0 0 0 8"/>

                </svg>

            </span>

            <span class="text-[25px] font-bold text-[#5b37ea]">
                OpenTune
            </span>

        </a>


        <!-- ==================== NAVIGATION ==================== -->
        <nav class="space-y-4">

            <!-- Home -->
            <a href="{{ route('home') }}"
               class="flex h-11 items-center justify-center rounded-xl border text-base font-semibold
               {{ request()->routeIs('home')
                    ? 'border-transparent bg-gradient-to-r from-[#d09cff] to-[#5d32eb] text-white'
                    : 'border-[#e1e3e9] text-[#34343a] hover:bg-[#faf9ff]' }}">

                Home
            </a>


            <!-- Your Music -->
            @auth
                <a href="{{ route('songs.mine') }}"
                   class="flex h-11 items-center justify-center rounded-xl border text-base font-semibold
                   {{ request()->routeIs('songs.mine')
                        ? 'border-transparent bg-gradient-to-r from-[#d09cff] to-[#5d32eb] text-white'
                        : 'border-[#e1e3e9] text-[#34343a] hover:bg-[#faf9ff]' }}">

                    Your Music

                </a>
            @else
                <a href="{{ route('login') }}"
                   class="flex h-11 items-center justify-center rounded-xl border border-[#e1e3e9] text-base font-semibold text-[#34343a] hover:bg-[#faf9ff]">

                    Your Music
                </a>
            @endauth
        </nav>

        <!-- ==================== PLAYLIST ==================== -->
        <div class="mt-4 flex min-h-0 flex-1 flex-col rounded-xl border border-[#e1e3e9]">

            <!-- Playlist Header -->
            <div class="flex items-center justify-between px-6 pt-5">

                <h2 id="playlist-panel-title"
                    class="text-[28px] font-semibold text-[#1b1b1f]">
                    Playlist
                </h2>
                @auth

                    <!-- Tombol tambah playlist -->
                    <button id="open-new-playlist"
                            type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-[#c89aff] to-[#5b2ff0] text-2xl text-white hover:opacity-90">
                        +
                    </button>

                @else

                    <!-- Jika belum login, arahkan ke login -->
                    <a href="{{ route('login') }}"
                       class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-[#c89aff] to-[#5b2ff0] text-2xl text-white hover:opacity-90">
                        +
                    </a>
                @endauth
            </div>

            <!-- Search Playlist -->
            <input id="playlist-search"
                   type="search"
                   placeholder="Cari playlist..."
                   class="mx-5 mt-3 h-10 rounded-lg border border-[#e6e3f0] px-3 text-sm outline-none focus:border-[#5a3be8]">

            <!-- ==================== PLAYLIST LIST ==================== -->
            <ul id="playlist-list"
                class="mt-3 flex-1 space-y-1 overflow-y-auto px-3 pb-4 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

                @foreach ($sidebarPlaylists as $item)

                    @php
                        // Cek apakah playlist sedang aktif
                        $active = request()->routeIs('playlists.*')
                            && optional(request()->route('playlist'))->id === $item->id;

                        // Nama pemilik playlist
                        $ownerName = auth()->user()->name ?? '';
                    @endphp

                    <li data-name="{{ strtolower($item->name) }}">

                        <a href="{{ route('playlists.show', $item) }}"
                           title="{{ $item->name }}"
                           class="flex h-[88px] items-start gap-2.5 rounded-[10px] px-2 py-1.5
                           {{ $active
                                ? 'bg-[#f1ebff]'
                                : 'hover:bg-[#faf9ff]' }}">


                            <!-- Kotak Playlist -->
                            <span class="h-[76px] w-[76px] flex-none rounded-md bg-gradient-to-br from-[#c89aff] to-[#5b2ff0]">
                            </span>


                            <!-- Nama Playlist + Username -->
                            <span class="min-w-0 flex-1 pt-3.5">
                                <span class="block truncate text-[18px] font-semibold text-[#1b1b1f]"
                                      title="{{ $item->name }}">

                                    {{ $item->name }}

                                </span>
                                <span class="block truncate text-[14px] text-[#8a8795]">

                                    By {{ $ownerName }}

                                </span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</aside>

<!-- ==================== MODAL PLAYLIST BARU ==================== -->

@auth
    <div id="new-playlist-modal"
         class="{{ $reopenPlaylistModal ? 'flex' : 'hidden' }} fixed inset-0 z-50 items-center justify-center bg-black/40 px-4">

        <div class="w-full max-w-[420px] rounded-2xl bg-white p-6">
            <h3 id="new-playlist-title"
                class="text-[22px] font-semibold">
                Playlist Baru
            </h3>

            <!-- Form -->
            <form action="{{ route('playlists.store') }}"
                  method="POST"
                  class="mt-5">
                @csrf
                <input type="hidden"
                       name="_from"
                       value="sidebar-playlist">
                <!-- Nama Playlist -->
                <label for="new-playlist-name"
                       class="mb-2 block text-sm font-medium">
                    Nama playlist
                </label>

                <input id="new-playlist-name"
                       name="name"
                       type="text"
                       value="{{ $reopenPlaylistModal ? old('name') : '' }}"
                       placeholder="Masukkan nama playlist"
                       maxlength="150"
                       required
                       class="h-11 w-full rounded-lg border px-3 text-sm outline-none focus:border-[#5a3be8]
                       {{ $reopenPlaylistModal
                            ? 'border-red-400'
                            : 'border-[#e6e3f0]' }}">


                <!-- Pesan Error -->
                @if ($reopenPlaylistModal)
                    <p class="mt-2 text-sm text-red-500">
                        {{ $errors->first('name') }}
                    </p>
                @endif

                <!-- Tombol -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button"
                            id="cancel-new-playlist"
                            class="h-10 rounded-lg border border-[#e1e3e9] px-5 text-sm font-semibold hover:bg-[#faf9ff]">
                        Batal
                    </button>
                    <button type="submit"
                            class="h-10 rounded-lg bg-gradient-to-br from-[#c89aff] to-[#5b2ff0] px-5 text-sm font-semibold text-white hover:opacity-90">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
@endauth
<!-- ==================== JAVASCRIPT ==================== -->
<script>

    // ==================== POP UP ====================

    const modal = document.getElementById('new-playlist-modal');
    const openButton = document.getElementById('open-new-playlist');
    const cancelButton = document.getElementById('cancel-new-playlist');
    const nameInput = document.getElementById('new-playlist-name');


    // Buka pop up
    if (modal && openButton) {

        openButton.addEventListener('click', () => {

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            nameInput.focus();

        });


        // Tutup pop up
        cancelButton.addEventListener('click', () => {

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            nameInput.value = '';

        });


        // Tutup jika klik di luar pop up
        modal.addEventListener('click', (event) => {

            if (event.target === modal) {

                modal.classList.add('hidden');
                modal.classList.remove('flex');

            }

        });


        // Tutup dengan tombol Escape
        document.addEventListener('keydown', (event) => {

            if (event.key === 'Escape') {

                modal.classList.add('hidden');
                modal.classList.remove('flex');

            }

        });


        // Fokus ke input jika pop up terbuka
        if (!modal.classList.contains('hidden')) {

            nameInput.focus();

        }

    }


    // ==================== SEARCH PLAYLIST ====================

    const search = document.getElementById('playlist-search');
    const playlists = document.querySelectorAll('#playlist-list li');


    if (search) {

        search.addEventListener('input', () => {

            const keyword = search.value.toLowerCase();


            playlists.forEach((playlist) => {

                playlist.classList.toggle(
                    'hidden',
                    !playlist.dataset.name.includes(keyword)
                );

            });

        });

    }

</script>
