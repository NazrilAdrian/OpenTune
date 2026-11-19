@extends('layouts.modul2', ['minimal' => true])
@section('title', 'OpenTune - Add Artist')
@section('content')
    <div class="mx-auto max-w-3xl pt-5 sm:pt-16">
        <div class="mb-7 text-center"><h1 class="text-4xl font-bold text-[#5d35eb]">Add Artist</h1><p class="mt-2 text-sm text-[#9799a1]">Tambahkan artist baru ke OpenTune.</p></div>
        <form action="{{ route('artists.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @include('artists.form')
            <div class="mt-5 flex gap-3"><button class="h-11 flex-1 rounded-xl bg-gradient-to-r from-[#d19cff] to-[#5b32eb] font-semibold text-white">Add Artist</button><a href="{{ route('home') }}" class="flex h-11 flex-1 items-center justify-center rounded-xl border border-[#dfe1e7] bg-white font-semibold">Cancel</a></div>
        </form>
    </div>
@endsection
