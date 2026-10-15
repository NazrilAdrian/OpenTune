@extends('admin.layout')
@section('title','Artist Management | OpenTune') @section('heading','Artist Management')
@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold">Artist Management</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola data artis di OpenTune.</p>
    </div>
    <a class="admin-btn" href="{{ route('admin.artists.create') }}">＋ Tambah Artist</a>
</div>
<div class="admin-card p-5">
    <form method="GET" class="mb-5 flex flex-wrap gap-2">
        <input class="admin-input min-w-[220px] flex-1" name="q" value="{{ $q }}" placeholder="Cari nama artist...">
        <button class="admin-btn">Cari</button>
        <a class="rounded-lg border px-4 py-2 text-sm" href="{{ route('admin.artists.index') }}">Reset</a>
    </form>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Artist</th>
                    <th>Biografi</th>
                    <th>Jumlah Album</th>
                    <th>Jumlah Lagu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($artists as $artist)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $artist->image_url }}" class="h-11 w-11 rounded-full object-cover" alt="">
                                <span class="font-semibold">{{ $artist->name }}</span>
                            </div>
                        </td>
                        <td class="max-w-sm">{{ \Illuminate\Support\Str::limit($artist->bio ?: '-', 90) }}</td>
                        <td>{{ $artist->albums_count }}</td>
                        <td>{{ $artist->songs_count }}</td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <details class="relative">
                                    <summary class="cursor-pointer list-none rounded-md bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                        View
                                    </summary>

                                    <div class="absolute right-0 z-20 mt-2 w-80 rounded-xl border border-gray-200 bg-white p-4 text-left shadow-xl">
                                        <p class="text-xs text-gray-500">Detail Artis</p>

                                        <div class="mt-2 flex items-center gap-3">
                                            <img
                                                src="{{ $artist->image_url }}"
                                                alt="Foto {{ $artist->name }}"
                                                class="h-14 w-14 rounded-full object-cover"
                                            >

                                            <h3 class="text-lg font-bold">
                                                {{ $artist->name }}
                                            </h3>
                                        </div>

                                        <p class="mt-3 text-xs text-gray-500">Biografi</p>
                                        <p class="mt-1 whitespace-normal break-words text-sm text-gray-600">
                                            {{ $artist->bio ?: 'Belum ada biografi.' }}
                                        </p>

                                        <div class="mt-3 grid grid-cols-2 gap-2 text-center text-xs">
                                            <div class="rounded-lg bg-gray-50 p-3">
                                                <b class="text-base">{{ $artist->albums_count }}</b>
                                                <p>Album</p>
                                            </div>

                                            <div class="rounded-lg bg-gray-50 p-3">
                                                <b class="text-base">{{ $artist->songs_count }}</b>
                                                <p>Lagu</p>
                                            </div>
                                        </div>

                                        <a
                                            class="mt-3 inline-block rounded-md bg-amber-50 px-3 py-1.5 text-xs text-amber-700"
                                            href="{{ route('admin.artists.edit', $artist->id) }}"
                                        >
                                            Edit Artis
                                        </a>
                                    </div>
                                </details>

                                <a
                                    class="rounded-md bg-amber-50 px-3 py-1.5 text-xs text-amber-700"
                                    href="{{ route('admin.artists.edit', $artist->id) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.artists.destroy', $artist->id) }}"
                                    onsubmit="return confirm('Hapus artist {{ addslashes($artist->name) }}?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="rounded-md bg-red-50 px-3 py-1.5 text-xs text-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">Artist tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $artists->links() }}</div>
</div>
@endsection;