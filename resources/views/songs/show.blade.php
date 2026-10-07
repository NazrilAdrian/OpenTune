@extends('layouts.modul2')

@section('title', 'OpenTune - '.$song->title)

@section('content')
    <section class="mx-auto max-w-5xl">
        <div class="grid gap-8 rounded-[28px] border border-[#e0e2e8] bg-white p-6 shadow-sm md:grid-cols-[320px_1fr] md:p-8">
            <div class="overflow-hidden rounded-2xl bg-[#ececf0]">
                <img src="{{ $song->cover_url }}" alt="Cover {{ $song->title }}" class="aspect-square w-full object-cover">
            </div>

            <div class="flex flex-col justify-end py-2">
                <p class="mb-2 text-sm font-semibold uppercase tracking-[0.18em] text-[#7b55e9]">Song</p>
                <h1 class="text-4xl font-bold tracking-[-0.045em] text-[#25262b] sm:text-5xl">{{ $song->title }}</h1>
                <a href="{{ route('artists.show', $song->artist) }}" class="mt-3 text-xl font-semibold text-[#575861] hover:text-[#5b32eb]">{{ $song->artist->name }}</a>

                <div class="mt-6 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                    <div><p class="text-[#989aa2]">Album</p><p class="mt-1 font-semibold">{{ $song->album?->name ?? 'Single' }}</p></div>
                    <div><p class="text-[#989aa2]">Genre</p><p class="mt-1 font-semibold">{{ $song->genre?->name ?? '-' }}</p></div>
                    <div><p class="text-[#989aa2]">Duration</p><p class="mt-1 font-semibold">{{ $song->duration_label }}</p></div>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <button type="button"
                        class="inline-flex h-11 items-center gap-2 rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] px-5 font-semibold text-white shadow-sm"
                        data-player-title="{{ e($song->title) }}"
                        data-player-artist="{{ e($song->artist->name) }}"
                        data-player-cover="{{ e($song->cover_url) }}"
                        data-player-audio="{{ e($song->audio_url) }}">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current"><path d="M8 5v14l11-7z"/></svg>
                        Putar
                    </button>
                    @can('update', $song)
                        <a href="{{ route('songs.edit', $song) }}" class="inline-flex h-11 items-center rounded-xl border border-[#dfe1e7] bg-white px-5 font-semibold text-[#474850]">Edit</a>
                    @endcan
                    @can('delete', $song)
                        <form action="{{ route('songs.destroy', $song) }}" method="POST" onsubmit="return confirm('Hapus lagu ini?')">
                            @csrf @method('DELETE')
                            <button class="inline-flex h-11 items-center rounded-xl border border-red-200 bg-white px-5 font-semibold text-red-600">Hapus</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </section>
@endsection
