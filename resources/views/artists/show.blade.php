@extends('layouts.modul2')

@section('title', 'OpenTune - '.$artist->name)

@section('content')
    <section class="mx-auto max-w-6xl">
        <div class="grid gap-7 rounded-[28px] border border-[#e0e2e8] bg-white p-6 shadow-sm md:grid-cols-[230px_1fr] md:p-8">
            <div class="overflow-hidden rounded-2xl bg-[#ececf0]">
                <img src="{{ $artist->image_url }}" alt="{{ $artist->name }}" class="aspect-square w-full object-cover">
            </div>
            <div class="self-end">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#7b55e9]">Artist</p>
                <h1 class="mt-2 text-4xl font-bold tracking-[-0.045em] text-[#25262b]">{{ $artist->name }}</h1>
                @if($artist->bio)<p class="mt-4 max-w-3xl leading-7 text-[#74767e]">{{ $artist->bio }}</p>@endif
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="mt-6 flex gap-3">
                            <a href="{{ route('artists.edit', $artist) }}" class="rounded-xl border border-[#dfe1e7] px-4 py-2 text-sm font-semibold">Edit Artist</a>
                            <form action="{{ route('artists.destroy', $artist) }}" method="POST" onsubmit="return confirm('Hapus artist ini?')">
                                @csrf @method('DELETE')
                                <button class="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600">Hapus</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <div class="mt-10">
            <h2 class="mb-5 text-2xl font-bold">Songs</h2>
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($artist->songs as $song)
                    <x-song-card :song="$song" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Belum ada lagu dari artist ini.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-10">
            <h2 class="mb-5 text-2xl font-bold">Albums</h2>
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($artist->albums as $album)
                    <x-album-card :album="$album" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Belum ada album dari artist ini.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
