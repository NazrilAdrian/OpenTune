@extends('layouts.app')

@section('title', $user->username . ' - Profile | OpenTune')

@section('content')
    {{-- Font Poppins (sesuai desain) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <div class="mx-auto w-full max-w-6xl space-y-6 font-['Poppins',sans-serif]">

        {{-- ===== Header Profil ===== --}}
        <section class="relative overflow-hidden rounded-2xl border border-[#e4def5] bg-gradient-to-b from-[#b79cf0] via-[#d3c6ee] to-[#f1eefa] shadow-sm">
            <div class="flex flex-col items-center gap-6 px-6 py-6 sm:flex-row sm:gap-10 sm:px-8 lg:gap-16">
                {{-- Foto profil --}}
                <img
                    src="{{ $user->profile_picture_url }}"
                    alt="Foto profil {{ $user->username }}"
                    class="h-32 w-32 shrink-0 rounded-full border-4 border-white object-cover shadow-lg shadow-[#5b34f0]/20 sm:h-36 sm:w-36"
                >

                {{-- Info --}}
                <div class="min-w-0 text-center sm:text-left">
                    <h1 class="truncate text-3xl font-bold tracking-tight text-black sm:text-4xl">{{ $user->username }}</h1>
                    <p class="mt-1 truncate text-lg text-[#1f1f23]">{{ $user->email }}</p>

                    <a href="{{ route('profile.edit') }}"
                       class="mt-4 inline-flex items-center rounded-md bg-[#5b34f0] px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-[#4b27d8] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/40 focus:ring-offset-2">
                        Edit Profile
                    </a>
                </div>
            </div>
        </section>

        {{-- ===== Top artist this month (UI dummy / statis) ===== --}}
        <section class="rounded-2xl border border-[#ece8f6] bg-white px-6 py-6 shadow-sm">
            <h2 class="mb-5 text-base font-medium text-[#2b2b2f]">Top artist this month</h2>

            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-6">
                @foreach (['#8b5cf6', '#6366f1', '#a855f7', '#7c3aed', '#9333ea', '#5b34f0'] as $color)
                    <div class="flex flex-col items-center text-center lg:items-start lg:text-left">
                        <div class="flex h-24 w-24 items-center justify-center rounded-full shadow-lg shadow-black/20 sm:h-28 sm:w-28"
                             style="background: linear-gradient(135deg, {{ $color }}, #1f1b2e);">
                            <svg class="h-10 w-10 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 18V5l12-2v13" />
                                <circle cx="6" cy="18" r="3" />
                                <circle cx="18" cy="16" r="3" />
                            </svg>
                        </div>
                        <p class="mt-3 text-base font-bold leading-tight text-black">Radiohead</p>
                        <p class="text-xs text-[#1f1f23]">Artist</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ===== Recently Played & My Playlist (UI dummy / statis) ===== --}}
        <div class="grid gap-6 lg:grid-cols-2">
            {{-- Recently Played --}}
            <section class="rounded-2xl border border-[#ece8f6] bg-white px-5 py-6 shadow-sm">
                <h2 class="mb-4 text-base font-medium text-[#2b2b2f]">Recently Played</h2>

                <div class="grid grid-cols-3 gap-4">
                    @foreach ([['#0f4c9c', '#f97316'], ['#1e3a8a', '#ea580c'], ['#0c4a8f', '#fb923c']] as [$from, $to])
                        <div class="group">
                            <div class="aspect-square w-full rounded-lg shadow-md transition group-hover:shadow-lg"
                                 style="background: linear-gradient(135deg, {{ $from }}, {{ $to }});"></div>
                            <p class="mt-2 truncate text-sm font-semibold text-black">Song Title</p>
                            <p class="truncate text-xs text-[#55555c]">Artist Name</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- My Playlist --}}
            <section class="rounded-2xl border border-[#ece8f6] bg-white px-5 py-6 shadow-sm">
                <h2 class="mb-4 text-base font-medium text-[#2b2b2f]">My Playlist</h2>

                <div class="grid grid-cols-3 gap-4">
                    @foreach ([['#0f4c9c', '#f97316'], ['#1e3a8a', '#ea580c'], ['#0c4a8f', '#fb923c']] as [$from, $to])
                        <div class="group">
                            <div class="aspect-square w-full rounded-lg shadow-md transition group-hover:shadow-lg"
                                 style="background: linear-gradient(135deg, {{ $from }}, {{ $to }});"></div>
                            <p class="mt-2 truncate text-sm font-semibold text-black">Playlist Name</p>
                            <p class="truncate text-xs text-[#55555c]">Playlist</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection
