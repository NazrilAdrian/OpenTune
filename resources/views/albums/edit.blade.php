@extends('layouts.modul2', ['minimal' => true])
@section('title', 'OpenTune - Edit Album')
@section('content')
    <div class="mx-auto max-w-[900px] pt-5 sm:pt-16">
        <header class="mb-7 text-center"><h1 class="text-3xl font-bold tracking-[-0.04em] text-[#5d35eb] sm:text-4xl">Edit Album</h1><p class="mt-2 text-sm text-[#9799a1]">Perbarui informasi album dan cover.</p></header>
        <form action="{{ route('albums.update', $album) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT') @include('albums.form')
            <div class="mt-6 flex gap-3"><button class="h-11 flex-1 rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] font-semibold text-white">Simpan Perubahan</button><a href="{{ route('albums.show', $album) }}" class="flex h-11 flex-1 items-center justify-center rounded-xl border border-[#dfe1e7] bg-white font-semibold">Cancel</a></div>
        </form>
    </div>
@endsection
