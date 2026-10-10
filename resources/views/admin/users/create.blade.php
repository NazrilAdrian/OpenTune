@extends('admin.layout')
@section('title','Tambah User | OpenTune') @section('heading','User Management')
@section('content')
<div class="mb-6"><a class="text-sm text-purple-600" href="{{ route('admin.users.index') }}">← Kembali ke User Management</a><h1 class="mt-3 text-2xl font-bold">Tambah Pengguna</h1></div>
<div class="admin-card max-w-2xl p-6"><form method="POST" action="{{ route('admin.users.store') }}">@csrf  @include('admin.users._form')<div class="mt-6 flex justify-end gap-3"><a class="rounded-lg border px-4 py-2 text-sm" href="{{ route('admin.users.index') }}">Batal</a><button class="admin-btn">Simpan</button></div></form></div>
@endsection
