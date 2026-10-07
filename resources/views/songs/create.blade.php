@extends('layouts.modul2', ['minimal' => true])

@section('title', 'OpenTune - Upload Lagu')

@section('content')
    <div class="mx-auto max-w-[1180px] pt-5 sm:pt-10">
        <header class="mb-7 text-center">
            <h1 class="text-3xl font-bold tracking-[-0.04em] text-[#5d35eb] sm:text-4xl">Upload Lagu</h1>
            <p class="mt-2 text-sm text-[#9799a1]">Unggah cover, isi informasi lagu, dan pilih file audio untuk dipublikasikan.</p>
        </header>

        <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('songs.form')
            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <button class="h-11 rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] font-semibold text-white shadow-sm">Upload Song</button>
                <a href="{{ url()->previous() }}" class="flex h-11 items-center justify-center rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] font-semibold text-white shadow-sm">Cancel</a>
            </div>
        </form>
        <p class="mt-3 text-center text-xs text-[#9a9ba2]">Pastikan file audio sesuai format MP3 atau M4A agar proses upload berhasil.</p>
    </div>
@endsection
