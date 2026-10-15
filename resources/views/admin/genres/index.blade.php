@extends('admin.layout')
@section('title','Genre Management | OpenTune') @section('heading','Genre Management')
@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold">Genre Management</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola kategori musik di OpenTune.</p>
    </div>
    <a class="admin-btn" href="{{ route('admin.genres.create') }}">＋ Tambah Genre</a>
</div>
<div class="admin-card p-5">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h2 class="font-bold">All Genre <span class="font-normal text-gray-400">: {{ $genres->total() }}</span></h2>
        <form method="GET" class="flex gap-2">
            <input class="admin-input min-w-[200px]" name="q" value="{{ $q }}" placeholder="Cari nama genre...">
            <button class="admin-btn">Cari</button>
        </form>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama Genre</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Lagu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($genres as $genre)
                    <tr>
                        <td class="font-semibold">{{ $genre->name }}</td>
                        <td class="max-w-sm">{{ $genre->description ?: '-' }}</td>
                        <td>{{ $genre->songs_count }}</td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <details class="relative">
                                    <summary class="cursor-pointer list-none rounded-md bg-blue-50 px-3 py-1.5 text-xs text-blue-700">View</summary>
                                    <div class="absolute right-0 z-20 mt-2 w-72 rounded-xl border border-gray-200 bg-white p-4 text-left shadow-xl">
                                        <p class="text-xs text-gray-500">Detail Genre</p>
                                        <h3 class="mt-1 text-lg font-bold">{{ $genre->name }}</h3>
                                        <p class="mt-2 text-sm text-gray-600">{{ $genre->description ?: 'Tidak ada deskripsi.' }}</p>
                                        <p class="mt-3 text-xs text-gray-500">Jumlah lagu: {{ $genre->songs_count }}</p>
                                        <a class="mt-3 inline-block rounded-md bg-amber-50 px-3 py-1.5 text-xs text-amber-700" href="{{ route('admin.genres.edit',$genre) }}">Edit genre</a>
                                    </div>
                                </details>
                                <a class="rounded-md bg-amber-50 px-3 py-1.5 text-xs text-amber-700" href="{{ route('admin.genres.edit',$genre) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.genres.destroy',$genre) }}" onsubmit="return confirm('Hapus genre {{ addslashes($genre->name) }}?')">@csrf @method('DELETE')<button class="rounded-md bg-red-50 px-3 py-1.5 text-xs text-red-600">Hapus</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400">Genre tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $genres->links() }}</div>
</div>
@endsection
