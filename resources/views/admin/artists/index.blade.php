@extends('admin.layout')
@section('title','Artist Management | OpenTune') @section('heading','Artist Management')
@section('content')
<div class="mb-6"><h1 class="text-2xl font-bold">Artist Management</h1><p class="mt-1 text-sm text-gray-500">Lihat dan kelola data artist di OpenTune.</p></div>
<div class="admin-card p-5"><div class="mb-5 flex flex-wrap items-center justify-between gap-3"><h2 class="font-bold">Popular Artist</h2><form method="GET" class="flex gap-2"><input class="admin-input min-w-[200px]" name="q" value="{{ $q }}" placeholder="Cari nama artist..."><button class="admin-btn">Cari</button></form></div>
<div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-6">@forelse($artists as $artist)<a href="{{ route('artists.show',$artist) }}" class="min-w-0 rounded-xl p-2 text-center hover:bg-purple-50"><img class="mx-auto aspect-square w-full max-w-[150px] rounded-full bg-gray-100 object-cover" src="{{ $artist->image_url }}" alt="Foto {{ $artist->name }}"><p class="mt-3 truncate text-sm font-semibold">{{ $artist->name }}</p><p class="mt-1 text-xs text-gray-400">{{ $artist->songs_count }} lagu · {{ $artist->albums_count }} album</p></a>@empty<p class="text-sm text-gray-400">Artist tidak ditemukan.</p>@endforelse</div><div class="mt-5">{{ $artists->links() }}</div></div>
@endsection
