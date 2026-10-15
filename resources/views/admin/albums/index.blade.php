@extends('admin.layout')
@section('title','Album Management | OpenTune') @section('heading','Album Management')
@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold">Album Management</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola seluruh album di OpenTune.</p>
    </div>
    <a class="admin-btn" href="{{ route('admin.albums.create') }}">＋ Tambah Album</a>
</div>
<div class="admin-card p-5">
    <form method="GET" class="mb-5 flex flex-wrap gap-2">
        <input class="admin-input min-w-[220px] flex-1" name="q" value="{{ $q }}" placeholder="Cari nama album atau artis...">
        <button class="admin-btn">Cari</button>
        <a class="rounded-lg border px-4 py-2 text-sm" href="{{ route('admin.albums.index') }}">Reset</a>
    </form>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Album</th>
                <th>Artis</th>
                <th>Tanggal Rilis</th>
                <th>Jumlah Lagu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($albums as $album)
        <tr>
            <td>
                <div class="flex items-center gap-3">
                    <img src="{{ $album->cover_url }}" class="h-11 w-11 rounded-lg object-cover" alt="">
                    <span class="font-semibold">{{ $album->name }}</span>
                </div>
            </td>
            <td>{{ $album->artist?->name ?? '-' }}</td>
            <td>{{ $album->release_date?->format('d M Y') ?? '-' }}</td>
            <td>{{ $album->songs_count }}</td>
            <td>
                <div class="flex flex-wrap gap-2">
                    <details class="relative">
                        <summary class="cursor-pointer list-none rounded-md bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                            View
                        </summary>

                        <div class="absolute right-0 z-20 mt-2 w-72 rounded-xl border border-gray-200 bg-white p-4 text-left shadow-xl">
                            <p class="text-xs text-gray-500">Detail Album</p>

                            <h3 class="mt-1 text-lg font-bold">
                                {{ $album->name }}
                            </h3>

                            <p class="mt-2 text-sm text-gray-600">
                                Artis: {{ $album->artist?->name ?? '-' }}
                            </p>

                            <p class="mt-1 text-sm text-gray-600">
                                Tanggal Rilis:
                                {{ $album->release_date?->format('d M Y') ?? '-' }}
                            </p>

                            <p class="mt-1 text-sm text-gray-600">
                                Jumlah Lagu: {{ $album->songs_count }}
                            </p>

                            <img
                                src="{{ $album->cover_url }}"
                                alt="Cover {{ $album->name }}"
                                class="mt-3 h-32 w-32 rounded-lg object-cover"
                            >

                            <a
                                class="mt-3 inline-block rounded-md bg-amber-50 px-3 py-1.5 text-xs text-amber-700"
                                href="{{ route('admin.albums.edit', $album->id) }}"
                            >
                                Edit Album
                            </a>
                        </div>
                    </details>

                    <a
                        class="rounded-md bg-amber-50 px-3 py-1.5 text-xs text-amber-700"
                        href="{{ route('admin.albums.edit', $album->id) }}"
                    >
                        Edit
                    </a>

                    <form
                        method="POST"
                        action="{{ route('admin.albums.destroy', $album->id) }}"
                        onsubmit="return confirm('Hapus album {{ addslashes($album->name) }}?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button class="rounded-md bg-red-50 px-3 py-1.5 text-xs text-red-600">
                            Hapus
                        </button>
                    </form>
                </div>
            </td>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="py-8 text-center text-gray-400">Album tidak ditemukan.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $albums->links() }}</div>
</div>
@endsection;