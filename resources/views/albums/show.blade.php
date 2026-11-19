@extends('layouts.modul2')
@section('title', 'OpenTune - '.$album->name)
@section('content')
    <section class="mx-auto max-w-6xl">
        <div class="grid gap-7 rounded-[28px] border border-[#e0e2e8] bg-white p-6 shadow-sm md:grid-cols-[280px_1fr] md:p-8">
            <div class="overflow-hidden rounded-2xl bg-[#ececf0]"><img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="aspect-square w-full object-cover"></div>
            <div class="self-end">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#7b55e9]">Album</p>
                <h1 class="mt-2 text-4xl font-bold tracking-[-0.045em] text-[#25262b]">{{ $album->name }}</h1>
                <a href="{{ route('artists.show', $album->artist) }}" class="mt-3 block text-xl font-semibold text-[#575861] hover:text-[#5b32eb]">{{ $album->artist->name }}</a>
                @if($album->release_date)<p class="mt-2 text-sm text-[#8b8d95]">Rilis {{ $album->release_date->format('d M Y') }}</p>@endif
                @can('update', $album)
                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('albums.edit', $album) }}" class="rounded-xl border border-[#dfe1e7] px-4 py-2 text-sm font-semibold">Edit Album</a>
                        <form action="{{ route('albums.destroy', $album) }}" method="POST" onsubmit="return confirm('Hapus album ini?')">
                            @csrf @method('DELETE')
                            <button class="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600">Hapus</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>

        <div class="mt-8 overflow-hidden rounded-2xl border border-[#e0e2e8] bg-white">
            <div class="border-b border-[#ebecf0] px-5 py-4"><h2 class="text-xl font-bold">Songs ({{ $album->songs->count() }})</h2></div>
            <div class="divide-y divide-[#f0f0f3]">
                @forelse($album->songs as $song)
                    <div class="flex items-center gap-3 px-5 py-4">
                        <img src="{{ $song->cover_url }}" alt="{{ $song->title }}" class="h-12 w-12 rounded-lg object-cover">
                        <div class="min-w-0 flex-1"><a href="{{ route('songs.show', $song) }}" class="block truncate font-semibold hover:text-[#5b32eb]">{{ $song->title }}</a><p class="text-xs text-[#8b8d95]">{{ $song->artist->name }}</p></div>
                        <span class="hidden text-xs text-[#8b8d95] sm:block">{{ $song->duration_label }}</span>
                        <button type="button" class="flex h-9 w-9 items-center justify-center rounded-full bg-[#f1efff] text-[#6035ef]" data-player-title="{{ e($song->title) }}" data-player-artist="{{ e($song->artist->name) }}" data-player-cover="{{ e($song->cover_url) }}" data-player-audio="{{ e($song->audio_url) }}" aria-label="Putar {{ $song->title }}"><svg viewBox="0 0 24 24" class="ml-0.5 h-4 w-4 fill-current"><path d="M8 5v14l11-7z"/></svg></button>
                    </div>
                @empty
                    <p class="px-5 py-12 text-center text-sm text-[#8b8d95]">Album ini belum memiliki lagu.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
