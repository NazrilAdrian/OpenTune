{{-- Ganti 'layouts.app' dengan nama layout milik Anda (yang meng-include partials.sidebar). --}}
@extends('layouts.app')

@section('content')
<main class="px-6 pt-[33px] font-['Poppins',system-ui,sans-serif] max-[1024px]:pt-6 max-[768px]:px-4 max-[768px]:pt-4">

    {{-- Hero --}}
    <section class="flex h-[310px] items-start gap-9 rounded-2xl bg-[linear-gradient(168.704deg,#eadbfb_14.639%,#ffffff_85.361%)] px-8 pt-[30px] max-[1024px]:gap-6 max-[1024px]:px-6 max-[768px]:h-auto max-[768px]:flex-col max-[768px]:gap-4 max-[768px]:px-4 max-[768px]:py-6" aria-labelledby="playlist-title">
        <div class="grid h-[240px] w-[240px] flex-none grid-cols-[repeat(2,120px)] grid-rows-[repeat(2,120px)] overflow-hidden rounded-xl max-[768px]:h-[160px] max-[768px]:w-[160px] max-[768px]:grid-cols-[repeat(2,80px)] max-[768px]:grid-rows-[repeat(2,80px)]" role="img" aria-label="Sampul {{ $playlist->name }}">
            <div class="bg-[linear-gradient(135deg,#c99bf5_14.639%,#8a5cf0_85.361%)]"></div>
            <div class="bg-[linear-gradient(135deg,#a77cf2_14.639%,#4b2fe5_85.361%)]"></div>
            <div class="bg-[linear-gradient(135deg,#7b55ee_14.639%,#3a22c4_85.361%)]"></div>
            <div class="bg-[linear-gradient(135deg,#c99bf5_14.639%,#5a3be8_85.361%)]"></div>
        </div>

        <div class="min-w-0 flex-1 pt-[66px] max-[768px]:w-full max-[768px]:flex-none max-[768px]:pt-0">
            <p class="text-[16px] font-medium text-[#1b1b1f]">Public Playlist</p>
            <h1 id="playlist-title" title="{{ $playlist->name }}" class="-mb-[18px] -ml-1 truncate text-[96px] font-bold text-[#1b1b1f] max-[1024px]:-mb-3 max-[1024px]:text-[64px] max-[768px]:mb-2 max-[768px]:ml-0 max-[768px]:text-[40px]">{{ $playlist->name }}</h1>
            <div class="relative flex h-7 items-center max-[1024px]:h-auto max-[1024px]:flex-wrap max-[1024px]:gap-y-1">
                <img class="h-7 w-7 flex-none" alt="Foto profil {{ $playlist->user->name }}" width="28" height="28" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 28 28'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop offset='.14639' stop-color='%23c99bf5'/%3E%3Cstop offset='.85361' stop-color='%234b2fe5'/%3E%3C/linearGradient%3E%3C/defs%3E%3Ccircle cx='14' cy='14' r='14' fill='url(%23g)'/%3E%3C/svg%3E">
                <span class="ml-2.5 mr-2.5 flex-none whitespace-nowrap text-[16px] font-semibold text-[#1b1b1f]">{{ $playlist->user->name }}</span>
                <span class="whitespace-nowrap text-[16px] text-[#8a8795]">•&nbsp; {{ $songs->count() }} lagu</span>
            </div>
        </div>
    </section>

    {{-- Kontrol playlist --}}
    <section class="mt-9 mr-[26px] ml-6 flex items-center max-[1024px]:mx-2 max-[768px]:mx-0 max-[768px]:flex-wrap max-[768px]:gap-3" aria-label="Kontrol playlist">
        <button class="flex h-16 w-16 flex-none items-center justify-center rounded-full bg-[linear-gradient(135deg,#c99bf5_14.639%,#4b2fe5_85.361%)] p-0 pr-0.5" type="button" aria-label="Putar playlist">
            <svg width="14" height="18" viewBox="0 0 14 18" aria-hidden="true"><path d="M2 2 L12 9 L2 16 Z" fill="#ffffff" stroke="#ffffff" stroke-width="2" stroke-linejoin="round"/></svg>
        </button>
        <div class="ml-6 flex items-center gap-3 max-[768px]:ml-4 max-[768px]:gap-1">
            <button class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f6f2fd] p-0 text-[22px] font-medium leading-none text-[#5a3be8]" type="button" aria-label="Acak">⇄</button>
            <button class="flex h-12 w-12 items-center justify-center rounded-full p-0 text-[22px] font-medium leading-none text-[#8a8795]" type="button" aria-label="Unduh">↓</button>
            <button class="flex h-12 w-12 items-center justify-center rounded-full p-0 text-[22px] font-medium leading-none text-[#8a8795]" type="button" aria-label="Tambahkan">+</button>
            <button class="flex h-12 w-12 items-center justify-center rounded-full p-0 text-[22px] font-medium leading-none text-[#8a8795]" type="button" aria-label="Opsi lainnya">•••</button>
        </div>
        <button class="mt-1 ml-auto flex items-center whitespace-pre p-0 text-[16px] text-[#8a8795]" type="button">Urutan khusus  ☰</button>
    </section>

    <div class="mt-4 mr-[26px] ml-6 flex flex-wrap gap-4 max-[1024px]:mx-2 max-[768px]:mx-0">
        <button class="flex h-[42px] w-[150px] items-center justify-center whitespace-pre rounded-[21px] border border-[#e6e3f0] bg-white p-0 text-[15px] font-medium text-[#1b1b1f]" type="button">+  Tambahkan</button>
        <button class="flex h-[42px] w-[186px] items-center justify-center whitespace-pre rounded-[21px] border border-[#e6e3f0] bg-white p-0 text-[15px] font-medium text-[#1b1b1f]" type="button">✎  Nama &amp; detail</button>
    </div>

    {{-- Daftar lagu --}}
    <section class="mx-4 mt-7 max-[1024px]:mx-0 max-[768px]:mt-6" aria-label="Daftar lagu">
        <div role="table" aria-label="Daftar lagu {{ $playlist->name }}">
            <div role="rowgroup">
                <div class="grid h-8 grid-cols-[48px_576px_260px_minmax(0,1fr)_150px] items-start pr-[30px] pl-4 text-[15px] font-medium text-[#8a8795] max-[1024px]:grid-cols-[48px_minmax(0,1fr)_minmax(0,1fr)_100px] max-[1024px]:pr-4 max-[768px]:grid-cols-[32px_minmax(0,1fr)_56px] max-[768px]:pr-3 max-[768px]:pl-2" role="row">
                    <span role="columnheader">#</span>
                    <span role="columnheader">Judul</span>
                    <span class="max-[768px]:hidden" role="columnheader">Album</span>
                    <span class="max-[1024px]:hidden" role="columnheader">Tanggal ditambahkan</span>
                    <span class="text-right" role="columnheader">Durasi</span>
                </div>
            </div>
            <div class="mx-2 h-px bg-[#e6e3f0]" role="presentation"></div>

            <div class="mt-[11px] flex flex-col gap-1" role="rowgroup">
                {{--
                    Sesuaikan nama kolom ($song->title, ->artist, ->album, ->duration)
                    dengan tabel songs milik Anda.
                --}}
                @forelse ($songs as $song)
                    <div class="grid h-16 grid-cols-[48px_576px_260px_minmax(0,1fr)_150px] items-center rounded-[10px] pr-[30px] pl-4 text-[15px] text-[#8a8795] max-[1024px]:grid-cols-[48px_minmax(0,1fr)_minmax(0,1fr)_100px] max-[1024px]:pr-4 max-[768px]:grid-cols-[32px_minmax(0,1fr)_56px] max-[768px]:pr-3 max-[768px]:pl-2" role="row">
                        <span class="text-[16px] font-medium" role="cell">{{ $loop->iteration }}</span>
                        <div class="flex min-w-0 items-center gap-4 max-[768px]:gap-3" role="cell">
                            <span class="h-[46px] w-[46px] flex-none rounded-md bg-[linear-gradient(135deg,#c99bf5_14.639%,#4b2fe5_85.361%)]" aria-hidden="true"></span>
                            <div class="min-w-0">
                                <p class="truncate text-[17px] font-semibold text-[#1b1b1f]">{{ $song->title }}</p>
                                <p class="-mt-[1.5px] truncate text-[14px] text-[#8a8795]">{{ $song->artist }}</p>
                            </div>
                        </div>
                        <span class="truncate max-[768px]:hidden" role="cell">{{ $song->album }}</span>
                        <span class="whitespace-nowrap max-[1024px]:hidden" role="cell">{{ $song->created_at->translatedFormat('d M Y') }}</span>
                        <span class="whitespace-nowrap text-right" role="cell">{{ $song->duration }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center px-4 py-16 text-center">
                        <p class="text-[18px] font-semibold text-[#1b1b1f]">Playlist ini masih kosong</p>
                        <p class="mt-1 text-[14px] text-[#8a8795]">Lagu yang kamu tambahkan akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
@endsection
