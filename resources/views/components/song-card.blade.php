
@props(['song', 'compact' => false, 'manage' => false])

<article class="group relative min-w-0">
    {{-- Cover Lagu --}}
    <div class="relative aspect-square overflow-hidden rounded-lg bg-[#ececf0] shadow-sm">
        <a href="{{ route('songs.show', $song) }}" class="block h-full w-full">
            <img
                src="{{ $song->cover_url }}"
                alt="Cover {{ $song->title }}"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
            >
        </a>

        {{-- Tombol Play --}}
        <button
            type="button"
            data-player-title="{{ $song->title }}"
            data-player-artist="{{ $song->artist?->name ?? 'Unknown Artist' }}"
            data-player-cover="{{ $song->cover_url }}"
            data-player-audio="{{ $song->audio_url }}"
            aria-label="Putar {{ $song->title }}"
            class="absolute bottom-3 right-3 flex h-10 w-10 translate-y-2 items-center justify-center rounded-full bg-gradient-to-r from-[#cfa0ff] to-[#6035ef] text-white opacity-0 shadow-lg transition duration-200 group-hover:translate-y-0 group-hover:opacity-100 focus:translate-y-0 focus:opacity-100"
        >
            <svg
                viewBox="0 0 24 24"
                class="ml-0.5 h-5 w-5 fill-current"
                aria-hidden="true"
            >
                <path d="M8 5v14l11-7z"/>
            </svg>
        </button>
    </div>

    {{-- Judul, Menu, dan Artis --}}
    <div class="mt-2 min-w-0">
        <div class="flex min-w-0 items-center gap-1">
            <a
                href="{{ route('songs.show', $song) }}"
                title="{{ $song->title }}"
                class="min-w-0 flex-1 truncate text-[17px] font-bold leading-6 text-[#303137] transition hover:text-[#5b32eb] {{ $compact ? 'sm:text-base' : '' }}"
            >
                {{ $song->title }}
            </a>

            {{-- Menu Titik Tiga --}}
            @if($manage)
                <details class="relative shrink-0">
                    <summary
                        aria-label="Kelola lagu {{ $song->title }}"
                        class="flex h-8 w-7 cursor-pointer list-none items-center justify-center rounded-lg text-xl leading-none text-[#777983] transition hover:bg-[#f1ebff] hover:text-[#5b32eb] [&::-webkit-details-marker]:hidden"
                    >
                        ⋮
                    </summary>

                    <div class="absolute right-0 top-full z-50 mt-1 w-36 overflow-hidden rounded-xl border border-[#e8e5f0] bg-white py-1 shadow-xl">
                        <a
                            href="{{ route('songs.edit', $song) }}"
                            class="block px-4 py-2.5 text-sm font-medium text-[#3d3b45] transition hover:bg-[#f7f4ff] hover:text-[#5b32eb]"
                        >
                            Edit lagu
                        </a>

                        <form
                            method="POST"
                            action="{{ route('songs.destroy', $song) }}"
                            onsubmit="return confirm('Yakin ingin menghapus lagu ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="block w-full px-4 py-2.5 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
                            >
                                Hapus lagu
                            </button>
                        </form>
                    </div>
                </details>
            @endif
        </div>

        {{-- Nama Artis --}}
        <p class="truncate text-[13px] leading-5 text-[#8b8d95]">
            {{ $song->artist?->name ?? 'Unknown Artist' }}
        </p>
    </div>
</article>
