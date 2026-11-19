@extends('admin.layout')
@section('title','Tambah Genre | OpenTune') @section('heading','Genre Management')
@section('content')
<div class="mb-6"><a class="text-sm text-purple-600" href="{{ route('admin.genres.index') }}">← Kembali ke Genre Management</a><h1 class="mt-3 text-2xl font-bold">Tambah Genre</h1></div>
<div class="admin-card max-w-2xl p-6"><form method="POST" action="{{ route('admin.genres.store') }}">@csrf  @include('admin.genres._form')<div class="mt-6 flex justify-end gap-3"><a class="rounded-lg border px-4 py-2 text-sm" href="{{ route('admin.genres.index') }}">Batal</a><button class="admin-btn">Simpan</button></div></form></div>
@endsection
