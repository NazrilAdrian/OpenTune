@extends('layouts.modul2')

@section('title', 'OpenTune - Search')

@section('content')
    <div class="space-y-10">
        <section>
            <h1 class="mb-5 text-3xl font-bold tracking-[-0.035em] text-[#303137]">
                {{ $q !== '' ? 'Result for “'.$q.'”' : 'Search' }}
            </h1>

            <h2 class="mb-5 text-2xl font-bold text-[#303137]">Song</h2>
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($songs as $song)
                    <x-song-card :song="$song" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Lagu tidak ditemukan.</p>
                @endforelse
            </div>
        </section>

        <section>
            <h2 class="mb-5 text-2xl font-bold text-[#303137]">Album</h2>
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($albums as $album)
                    <x-album-card :album="$album" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Album tidak ditemukan.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
