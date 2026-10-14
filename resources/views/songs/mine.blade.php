
@extends('layouts.modul2')

@section('title', 'OpenTune - Collection')

@section('content')
    <section>
        {{-- Header Collection --}}
        <div class="mb-5">
            <h1 class="text-3xl font-bold tracking-[-0.035em] text-[#303137]">
                Collection
            </h1>
        </div>

        {{-- Tab Song dan Album --}}
        <div class="mb-6 flex gap-7 border-b border-[#e2e3e8]">
            <a
                href="{{ route('songs.mine', ['tab' => 'song']) }}"
                class="relative pb-3 text-lg font-bold transition
                    {{ $tab === 'song'
                        ? 'text-[#303137] after:absolute after:bottom-[-1px] after:left-0 after:h-[3px] after:w-full after:rounded-full after:bg-black'
                        : 'text-[#92939a] hover:text-[#5b32eb]' }}"
            >
                Song
            </a>

            <a
                href="{{ route('songs.mine', ['tab' => 'album']) }}"
                class="relative pb-3 text-lg font-bold transition
                    {{ $tab === 'album'
                        ? 'text-[#303137] after:absolute after:bottom-[-1px] after:left-0 after:h-[3px] after:w-full after:rounded-full after:bg-black'
                        : 'text-[#92939a] hover:text-[#5b32eb]' }}"
            >
                Album
            </a>
        </div>

        {{-- Daftar Album --}}
        @if($tab === 'album')
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($albums as $album)
                    <x-album-card
                        :album="$album"
                        :manage="true"
                    />
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center">
                        <p class="text-sm text-[#8b8d95]">
                            Kamu belum memiliki album.
                        </p>
                    </div>
                @endforelse
            </div>

        {{-- Daftar Lagu --}}
        @else
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($songs as $song)
                    <x-song-card
                        :song="$song"
                        :manage="true"
                    />
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center">
                        <p class="text-sm text-[#8b8d95]">
                            Kamu belum mengupload lagu.
                        </p>
                    </div>
                @endforelse
            </div>
        @endif
    </section>
@endsection
