
@props(['album', 'manage' => false])

<article class="group relative min-w-0">
    {{-- Cover Album --}}
    <div class="aspect-square overflow-hidden rounded-lg bg-[#ececf0] shadow-sm">
        <a href="{{ route('albums.show', $album) }}" class="block h-full w-full">
            <img
                src="{{ $album->cover_url }}"
                alt="Cover {{ $album->name }}"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
            >
        </a>
    </div>

    {{-- Judul, Menu, dan Artis --}}
    <div class="mt-2 min-w-0">
        <div class="flex min-w-0 items-center gap-1">
            <a
                href="{{ route('albums.show', $album) }}"
                title="{{ $album->name }}"
                class="min-w-0 flex-1 truncate text-[17px] font-bold leading-6 text-[#303137] transition hover:text-[#5b32eb]"
            >
                {{ $album->name }}
            </a>

            {{-- Menu Titik Tiga --}}
            @if($manage)
                <details class="relative shrink-0">
                    <summary
                        aria-label="Kelola album {{ $album->name }}"
                        class="flex h-8 w-7 cursor-pointer list-none items-center justify-center rounded-lg text-xl leading-none text-[#777983] transition hover:bg-[#f1ebff] hover:text-[#5b32eb] [&::-webkit-details-marker]:hidden"
                    >
                        ⋮
                    </summary>

                    <div class="absolute right-0 top-full z-50 mt-1 w-36 overflow-hidden rounded-xl border border-[#e8e5f0] bg-white py-1 shadow-xl">
                        <a
                            href="{{ route('albums.edit', $album) }}"
                            class="block px-4 py-2.5 text-sm font-medium text-[#3d3b45] transition hover:bg-[#f7f4ff] hover:text-[#5b32eb]"
                        >
                            Edit album
                        </a>

                        <form
                            method="POST"
                            action="{{ route('albums.destroy', $album) }}"
                            onsubmit="return confirm('Yakin ingin menghapus album ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="block w-full px-4 py-2.5 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
                            >
                                Hapus album
                            </button>
                        </form>
                    </div>
                </details>
            @endif
        </div>

        {{-- Nama Artis --}}
        <p class="truncate text-[13px] leading-5 text-[#8b8d95]">
            {{ $album->artist?->name ?? 'Unknown Artist' }}
        </p>
    </div>
</article>
