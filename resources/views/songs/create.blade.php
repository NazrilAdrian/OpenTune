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
                <button type="submit" class="upload-form-action">Upload Song</button>
                <a href="{{ route('songs.mine') }}" class="upload-form-action">Cancel</a>
            </div>
        </form>
        <p class="mt-3 text-center text-xs text-[#9a9ba2]">Pastikan file audio sesuai format MP3 atau M4A agar proses upload berhasil.</p>
    </div>
@endsection
