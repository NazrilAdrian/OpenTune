@props(['album'])

<article class="group min-w-0">
    <div class="aspect-square overflow-hidden rounded-lg bg-[#ececf0] shadow-sm">
        <a href="{{ route('albums.show', $album) }}" class="block h-full w-full">
            <img src="{{ $album->cover_url }}" alt="Cover {{ $album->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
        </a>
    </div>
    <div class="mt-2 min-w-0">
        <a href="{{ route('albums.show', $album) }}" class="block truncate text-[17px] font-bold leading-6 text-[#303137] hover:text-[#5b32eb]">{{ $album->name }}</a>
        <p class="truncate text-[13px] leading-5 text-[#8b8d95]">{{ $album->artist?->name ?? 'Unknown Artist' }}</p>
    </div>
</article>
