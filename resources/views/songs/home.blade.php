@extends('layouts.modul2')

@section('title', 'OpenTune - Home')

@section('content')
    <div class="space-y-10">
        <section>
            <div class="mb-5 flex items-end justify-between gap-4">
                <h1 class="text-3xl font-bold tracking-[-0.035em] text-[#303137]">Recommendation</h1>
                @if($recommendations->count() > 6)
                    <a href="{{ route('search') }}" class="text-sm font-semibold text-[#6836eb]">Lihat semua</a>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($recommendations as $song)
                    <x-song-card :song="$song" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Belum ada lagu untuk direkomendasikan.</p>
                @endforelse
            </div>
        </section>

        <section>
            <h2 class="mb-5 text-3xl font-bold tracking-[-0.035em] text-[#303137]">Just Uploaded</h2>
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($latest as $song)
                    <x-song-card :song="$song" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Belum ada lagu yang diupload.</p>
                @endforelse
            </div>
        </section>

        <section>
            <h2 class="mb-5 text-3xl font-bold tracking-[-0.035em] text-[#303137]">Most Listened This Month</h2>
            <div class="grid grid-cols-2 gap-x-4 gap-y-7 sm:grid-cols-3 xl:grid-cols-6">
                @forelse($mostListened as $song)
                    <x-song-card :song="$song" />
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-[#dcdde4] px-5 py-12 text-center text-sm text-[#8b8d95]">Belum ada data lagu.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
