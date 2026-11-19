@extends('layouts.modul2', ['minimal' => true])
@section('title', 'OpenTune - Edit Artist')
@section('content')
    <div class="mx-auto max-w-3xl pt-5 sm:pt-16">
        <div class="mb-7 text-center"><h1 class="text-4xl font-bold text-[#5d35eb]">Edit Artist</h1><p class="mt-2 text-sm text-[#9799a1]">Perbarui informasi artist.</p></div>
        <form action="{{ route('artists.update', $artist) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT') @include('artists.form')
            <div class="mt-5 flex gap-3"><button class="h-11 flex-1 rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] font-semibold text-white">Simpan</button><a href="{{ route('artists.show', $artist) }}" class="flex h-11 flex-1 items-center justify-center rounded-xl border border-[#dfe1e7] bg-white font-semibold">Cancel</a></div>
        </form>
    </div>
@endsection
