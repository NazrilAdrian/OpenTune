@extends('admin.layout')
@section('title','Tambah Lagu | OpenTune')
@section('heading','Song Management')
@section('content')
<div class="mb-6">
    <a class="text-sm text-purple-600" href="{{ route('admin.songs.index') }}">← Kembali ke Song Management</a>
    <h1 class="mt-3 text-2xl font-bold">Tambah Lagu</h1>
    <p class="mt-1 text-sm text-gray-500">Isi metadata lagu dan pilih file yang sesuai.</p>
</div>
<div class="admin-card max-w-4xl p-5 sm:p-7">
    <form method="POST" action="{{ route('admin.songs.store') }}" enctype="multipart/form-data">@csrf  @include('admin.songs._form')
        <div class="mt-6 flex justify-end gap-3">
            <a class="rounded-lg border px-4 py-2 text-sm" href="{{ route('admin.songs.index') }}">Batal</a>
            <button class="admin-btn">Simpan</button>
        </div>
    </form>
</div>
@endsection