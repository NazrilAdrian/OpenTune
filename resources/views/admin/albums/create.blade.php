@extends('admin.layout')
@section('title','Tambah Album | OpenTune') @section('heading','Album Management')
@section('content')
<div class="mb-6">
    <a class="text-sm text-purple-600" href="{{ route('admin.albums.index') }}">← Kembali ke Album Management</a>
    <h1 class="mt-3 text-2xl font-bold">Tambah Album</h1>
</div>
<div class="admin-card max-w-2xl p-6">
    <form method="POST" action="{{ route('admin.albums.store') }}" enctype="multipart/form-data">
        @csrf @include('admin.albums._form')
        <div class="mt-6 flex justify-end gap-3">
            <a class="rounded-lg border px-4 py-2 text-sm" href="{{ route('admin.albums.index') }}">Batal</a>
            <button class="admin-btn">Simpan Album</button>
        </div>
    </form>
</div>
@endsection