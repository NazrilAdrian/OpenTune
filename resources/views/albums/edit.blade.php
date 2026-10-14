@extends('layouts.modul2', ['minimal' => true])
@section('title', 'OpenTune - Edit Album')
@section('content')
    <div class="mx-auto max-w-[900px] pt-5 sm:pt-16">
        <header class="mb-7 text-center"><h1 class="text-3xl font-bold tracking-[-0.04em] text-[#5d35eb] sm:text-4xl">Edit Album</h1><p class="mt-2 text-sm text-[#9799a1]">Perbarui informasi album dan cover.</p></header>
        <form action="{{ route('albums.update', $album) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT') @include('albums.form')
            <div class="mt-6 flex gap-3"><button type="submit" class="upload-form-action flex-1">Simpan Perubahan</button><a href="{{ route('albums.show', $album) }}" class="upload-form-action flex-1">Cancel</a></div>
        </form>
    </div>
@endsection
