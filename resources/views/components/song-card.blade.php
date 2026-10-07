@props(['song', 'compact' => false])

<article class="group min-w-0">
    <div class="relative aspect-square overflow-hidden rounded-lg bg-[#ececf0] shadow-sm">
        <a href="{{ route('songs.show', $song) }}" class="block h-full w-full">
            <img src="{{ $song->cover_url }}" alt="Cover {{ $song->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
        </a>
        <button type="button"
            class="absolute bottom-3 right-3 flex h-10 w-10 translate-y-2 items-center justify-center rounded-full bg-gradient-to-r from-[#cfa0ff] to-[#6035ef] text-white opacity-0 shadow-lg transition duration-200 group-hover:translate-y-0 group-hover:opacity-100 focus:translate-y-0 focus:opacity-100"
            data-player-title="{{ e($song->title) }}"
            data-player-artist="{{ e($song->artist?->name ?? 'Unknown Artist') }}"
            data-player-cover="{{ e($song->cover_url) }}"
            data-player-audio="{{ e($song->audio_url) }}"
            aria-label="Putar {{ $song->title }}">
            <svg viewBox="0 0 24 24" class="ml-0.5 h-5 w-5 fill-current"><path d="M8 5v14l11-7z"/></svg>
        </button>
    </div>
    <div class="mt-2 min-w-0">
        <a href="{{ route('songs.show', $song) }}" class="block truncate text-[17px] font-bold leading-6 text-[#303137] hover:text-[#5b32eb] {{ $compact ? 'sm:text-base' : '' }}">
            {{ $song->title }}
        </a>
        <p class="truncate text-[13px] leading-5 text-[#8b8d95]">{{ $song->artist?->name ?? 'Unknown Artist' }}</p>
    </div>
</article>
