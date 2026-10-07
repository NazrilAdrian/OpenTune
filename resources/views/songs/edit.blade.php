@extends('layouts.modul2', ['minimal' => true])

@section('title', 'OpenTune - Edit Lagu')

@section('content')
    <div class="mx-auto max-w-[1180px] pt-5 sm:pt-10">
        <header class="mb-7 text-center">
            <h1 class="text-3xl font-bold tracking-[-0.04em] text-[#5d35eb] sm:text-4xl">Edit Lagu</h1>
            <p class="mt-2 text-sm text-[#9799a1]">Perbarui metadata, cover, atau file audio lagu.</p>
        </header>
        <form action="{{ route('songs.update', $song) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('songs.form')
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <button class="h-11 flex-1 rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] font-semibold text-white shadow-sm">Simpan Perubahan</button>
                <a href="{{ route('songs.show', $song) }}" class="flex h-11 flex-1 items-center justify-center rounded-xl border border-[#dfe1e7] bg-white font-semibold text-[#4a4b52]">Batal</a>
            </div>
        </form>
    </div>
@endsection
