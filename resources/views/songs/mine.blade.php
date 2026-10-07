@extends('layouts.modul2')

@section('title', 'OpenTune - Collection')

@section('content')
    <section>
        <div class="mb-5 flex items-center justify-between gap-4">
            <h1 class="text-3xl font-bold tracking-[-0.035em] text-[#303137]">Collection</h1>
            <div class="hidden gap-2 sm:flex">
                <a href="{{ route('songs.create') }}" class="rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] px-4 py-2 text-sm font-semibold text-white">Upload Song</a>
                <a href="{{ route('albums.create') }}" class="rounded-xl border border-[#e0e2e8] bg-white px-4 py-2 text-sm font-semibold text-[#4a4b52]">Upload Album</a>
            </div>
        </div>

        <div class="mb-6 flex gap-7 border-b border-[#e2e3e8]">
            <a href="{{ route('songs.mine', ['tab' => 'song']) }}" class="relative pb-3 text-lg font-bold {{ $tab === 'song' ? 'text-[#303137] after:absolute after:bottom-[-1px] after:left-0 after:h-[3px] after:w-full after:rounded-full after:bg-black' : 'text-[#92939a]' }}">Song</a>
            <a href="{{ route('songs.mine', ['tab' => 'album']) }}" class="relative pb-3 text-lg font-bold {{ $tab === 'album' ? 'text-[#303137] after:absolute after:bottom-[-1px] after:left-0 after:h-[3px] after:w-full after:rounded-full after:bg-black' : 'text-[#92939a]' }}">Album</a>
        </div>

        @if($tab === 'album')
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($albums as $album)
                    <x-album-card :album="$album" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Kamu belum memiliki album.</p>
                @endforelse
            </div>
        @else
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($songs as $song)
                    <x-song-card :song="$song" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Kamu belum mengupload lagu.</p>
                @endforelse
            </div>
        @endif
    </section>
@endsection
