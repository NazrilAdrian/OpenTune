@extends('admin.layout')
@section('title','Edit Artist | OpenTune') @section('heading','Artist Management')
@section('content')
<div class="mb-6">
    <a class="text-sm text-purple-600" href="{{ route('admin.artists.index') }}">← Kembali ke Artist Management</a>
    <h1 class="mt-3 text-2xl font-bold">Edit Artist</h1>
</div>
<div class="admin-card max-w-2xl p-6">
    <form method="POST" action="{{ route('admin.artists.update', $artist->id) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.artists._form')
        <div class="mt-6 flex justify-end gap-3">
            <a class="rounded-lg border px-4 py-2 text-sm" href="{{ route('admin.artists.index') }}">Batal</a>
            <button class="admin-btn">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection