<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenTune - Discover, create, &amp; share your music</title>
    <meta name="description" content="OpenTune adalah aplikasi web pemutar musik untuk mengunggah, mengelola, mencari, dan memutar lagu serta membuat playlist pribadi.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-white font-['Poppins',sans-serif] text-[#2b2b2f] antialiased">

    {{-- ===== Background ungu lembut di pojok kanan atas ===== --}}
    <div class="pointer-events-none absolute inset-x-0 top-0 -z-0 h-[760px] overflow-hidden" aria-hidden="true">
        <div class="absolute -right-32 -top-40 h-[560px] w-[760px] rounded-full bg-[#efe8fc] blur-3xl"></div>
    </div>

    {{-- ===== Navbar ===== --}}
    <header class="relative z-20">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-10" aria-label="Navigasi utama">
            {{-- Logo --}}
            <a href="{{ url('/landing') }}" class="flex items-center gap-2" aria-label="OpenTune">
                <svg class="h-9 w-9" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <linearGradient id="otLogoGradLanding" x1="4" y1="4" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#B07CF5" />
                            <stop offset="1" stop-color="#5B34F0" />
                        </linearGradient>
                    </defs>
                    <circle cx="20" cy="20" r="18" stroke="url(#otLogoGradLanding)" stroke-width="3" />
                    <path d="M12 23v-2.5a8 8 0 0 1 16 0V23" stroke="url(#otLogoGradLanding)" stroke-width="2.6" stroke-linecap="round" />
                    <rect x="10.5" y="22" width="4.5" height="7" rx="2" fill="url(#otLogoGradLanding)" />
                    <rect x="25" y="22" width="4.5" height="7" rx="2" fill="url(#otLogoGradLanding)" />
                </svg>
                <span class="text-xl font-semibold tracking-tight text-[#5b34f0]">OpenTune</span>
            </a>

            {{-- Menu tengah --}}
            <ul class="hidden items-center gap-12 md:flex">
                <li><a href="{{ url('/landing') }}" class="font-semibold text-[#2b2b2f]">Home</a></li>
                <li><a href="#features" class="text-[#3a3a40] transition hover:text-[#5b34f0]">Features</a></li>
            </ul>

            {{-- Auth --}}
            <div class="flex items-center gap-3 sm:gap-6">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#5b34f0] transition hover:text-[#4b27d8] sm:text-base">Login</a>
                <a href="{{ route('register') }}"
                   class="rounded-md bg-[#5b34f0] px-5 py-2 text-sm font-semibold text-white shadow-sm shadow-[#5b34f0]/30 transition hover:bg-[#4b27d8] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/40 focus:ring-offset-2 sm:px-6 sm:text-base">
                    Sign up
                </a>
            </div>
        </nav>
    </header>

    <main class="relative z-10">

        {{-- ===== Hero ===== --}}
        <section class="mx-auto grid max-w-7xl items-center gap-12 px-6 pb-20 pt-8 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:px-10 lg:pt-12">

            {{-- Teks --}}
            <div>
                <h1 class="text-5xl font-extrabold leading-[1.1] tracking-tight text-[#2b2b2f] sm:text-6xl">
                    Discover, create, &amp; share your music
                </h1>
                <p class="mt-8 max-w-lg text-lg leading-[2.2rem] text-[#3a3a40]">
                    Welcome to OpenTune. Access thousands of songs, albums, and playlist anytime. Upload your own tracks and join the rhythm.
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-4">
                    <a href="{{ route('register') }}"
                       class="rounded-full bg-gradient-to-r from-[#b78cf7] to-[#5b34f0] px-9 py-3 text-sm font-semibold text-white shadow-lg shadow-[#5b34f0]/30 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#5b34f0]/40 focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/40 focus:ring-offset-2">
                        Get Started
                    </a>
                    <a href="#features"
                       class="rounded-full border border-[#e3e0ec] bg-white px-9 py-3 text-sm font-semibold text-[#5b34f0] transition hover:bg-[#f7f5fd] focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/20">
                        Learn More
                    </a>
                </div>
            </div>

            {{-- Visual: awan ungu + mockup aplikasi (dummy) --}}
            <div class="relative mx-auto h-[360px] w-full max-w-[640px] sm:h-[440px] lg:mx-0 lg:h-[500px] lg:max-w-none" aria-hidden="true">

                {{-- Awan --}}
                <div class="absolute inset-0 lg:-right-24">
                    <div class="absolute bottom-[4%] left-[6%] h-[34%] w-[34%] rounded-full bg-[#cdbfe9] shadow-xl shadow-[#5b34f0]/10"></div>
                    <div class="absolute bottom-[0%] left-[24%] h-[40%] w-[50%] rounded-full bg-[#c4b3e6] shadow-xl shadow-[#5b34f0]/10"></div>
                    <div class="absolute bottom-[6%] right-[8%] h-[34%] w-[34%] rounded-full bg-[#cabbe8] shadow-xl shadow-[#5b34f0]/10"></div>
                    <div class="absolute left-[2%] top-[18%] h-[40%] w-[28%] rounded-full bg-[#d6c9f0] shadow-xl shadow-[#5b34f0]/10"></div>
                    <div class="absolute right-[2%] top-[4%] h-[56%] w-[70%] rounded-full bg-gradient-to-b from-[#b9a3e8] to-[#d3c6ee] shadow-xl shadow-[#5b34f0]/10"></div>
                    <div class="absolute left-[18%] top-[6%] h-[44%] w-[56%] rounded-full bg-gradient-to-b from-[#b4a0e6] to-[#d0c2ec]"></div>
                    <div class="absolute bottom-[10%] left-[14%] right-[10%] top-[30%] rounded-[3rem] bg-gradient-to-b from-[#c9bae8] to-[#d9cef1]"></div>
                </div>

                {{-- Window aplikasi --}}
                <div class="absolute left-[8%] top-[17%] w-[116%] overflow-hidden rounded-md border border-[#e4e0f0] bg-white shadow-2xl shadow-[#3b1fa8]/25 sm:left-[10%] lg:left-[6%] lg:w-[112%]">
                    <div class="flex">
                        {{-- Sidebar --}}
                        <aside class="w-[20%] shrink-0 border-r border-[#eeeaf6] p-2.5">
                            <div class="mb-3 flex items-center gap-1">
                                <span class="h-3 w-3 rounded-full border-2 border-[#8b5cf6]"></span>
                                <span class="text-[8px] font-semibold text-[#5b34f0]">OpenTune</span>
                            </div>
                            <div class="rounded-sm bg-gradient-to-r from-[#b78cf7] to-[#7c4dff] py-1 text-center text-[7px] font-semibold text-white">Home</div>
                            <p class="my-2 text-center text-[7px] text-[#3a3a40]">Your Music</p>
                            <div class="rounded-sm border border-[#eeeaf6] p-1.5">
                                <div class="mb-1.5 flex items-center justify-between">
                                    <span class="text-[8px] font-semibold text-[#2b2b2f]">Playlist</span>
                                    <span class="h-2.5 w-2.5 rounded-sm bg-[#8b5cf6]"></span>
                                </div>
                                <div class="space-y-1.5">
                                    @foreach (range(1, 7) as $n)
                                        <div class="flex items-center gap-1">
                                            <span class="h-4 w-4 shrink-0 rounded-sm bg-gradient-to-br from-[#c9a7fa] to-[#6d3df5]"></span>
                                            <span class="min-w-0">
                                                <span class="block truncate text-[5px] font-semibold text-[#2b2b2f]">Playlist #{{ $n }}</span>
                                                <span class="block truncate text-[4px] text-[#8a8a92]">By OpenTune</span>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </aside>

                        {{-- Konten --}}
                        <div class="min-w-0 flex-1 p-3">
                            <div class="mb-3 flex items-center justify-between gap-4">
                                <span class="h-3 w-[40%] rounded-sm border border-[#eeeaf6]"></span>
                                <div class="flex items-center gap-2">
                                    <span class="rounded-sm bg-gradient-to-r from-[#b78cf7] to-[#5b34f0] px-3 py-0.5 text-[6px] font-semibold text-white">Upload</span>
                                    <span class="h-3.5 w-3.5 rounded-full bg-[#9a9aa2]"></span>
                                </div>
                            </div>

                            {{-- Recommendation --}}
                            <p class="mb-1 text-[7px] font-semibold text-[#2b2b2f]">Recommendation</p>
                            <div class="flex gap-2 overflow-hidden">
                                @foreach ([
                                    ['Setsunarensa', 'from-[#7a8c9e] to-[#2b3a4a]'],
                                    ['Dramaturgy', 'from-[#f2f2f2] to-[#cfcfcf]'],
                                    ['Starlight Para...', 'from-[#2b2b2b] to-[#111111]'],
                                    ['ODO', 'from-[#7a3fbf] to-[#e0407a]'],
                                    ['Hyperventilat...', 'from-[#f1ece4] to-[#d8cfc2]'],
                                    ['Mukanjyo', 'from-[#2b3a2f] to-[#7ea08a]'],
                                    ['Fukutsu', 'from-[#3a3a3a] to-[#9a9a9a]'],
                                ] as [$title, $grad])
                                    <div class="w-[13%] shrink-0">
                                        <div class="aspect-square w-full rounded-sm bg-gradient-to-br {{ $grad }}"></div>
                                        <p class="mt-0.5 truncate text-[6px] font-semibold text-[#2b2b2f]">{{ $title }}</p>
                                        <p class="truncate text-[4px] text-[#8a8a92]">Artist</p>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Just Uploaded --}}
                            <p class="mb-1 mt-3 text-[7px] font-semibold text-[#2b2b2f]">Just Uploaded</p>
                            <div class="flex gap-2 overflow-hidden">
                                @foreach (range(1, 7) as $n)
                                    <div class="w-[13%] shrink-0">
                                        <div class="aspect-square w-full rounded-sm bg-gradient-to-br from-[#c9a7fa] to-[#6d3df5]"></div>
                                        <p class="mt-0.5 truncate text-[6px] font-semibold text-[#2b2b2f]">Sample</p>
                                        <p class="truncate text-[4px] text-[#8a8a92]">Sample</p>
                                    </div>
                                @endforeach
                            </div>

                            {{-- This Month Most Listened --}}
                            <p class="mb-1 mt-3 text-[7px] font-semibold text-[#2b2b2f]">This Month Most Listened</p>
                            <div class="flex gap-2 overflow-hidden">
                                @foreach (range(1, 7) as $n)
                                    <div class="w-[13%] shrink-0">
                                        <div class="aspect-square w-full rounded-sm bg-gradient-to-br from-[#c9a7fa] to-[#6d3df5]"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Player bar --}}
                    <div class="flex items-center gap-3 border-t border-[#eeeaf6] bg-white px-3 py-1.5">
                        <span class="h-4 w-4 rounded-sm bg-gradient-to-br from-[#7a8c9e] to-[#2b3a4a]"></span>
                        <span class="text-[6px] font-semibold text-[#2b2b2f]">Setsunarensa</span>
                        <div class="mx-auto flex items-center gap-2">
                            <span class="text-[6px] text-[#3a3a40]">&#9198;</span>
                            <span class="flex h-4 w-4 items-center justify-center rounded-full bg-gradient-to-br from-[#b78cf7] to-[#5b34f0] text-[6px] text-white">&#9654;</span>
                            <span class="text-[6px] text-[#3a3a40]">&#9197;</span>
                        </div>
                        <span class="h-0.5 w-12 rounded-full bg-[#8b5cf6]"></span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== Features (dummy / statis) ===== --}}
        <section id="features" class="mx-auto max-w-7xl scroll-mt-8 px-6 pb-24 pt-8 lg:px-10">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-[#2b2b2f] sm:text-4xl">Everything you need for your music</h2>
                <p class="mt-4 text-base text-[#55555c]">Kelola koleksi musikmu, buat playlist favorit, dan temukan lagu baru dalam satu tempat.</p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ([
                    ['Upload Your Tracks', 'Unggah lagu satuan atau satu album sekaligus dalam bentuk ZIP, lengkap dengan metadata dan cover.', 'M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2'],
                    ['Build Your Playlists', 'Buat playlist pribadi, tambahkan lagu, dan atur urutannya sesuai suasana hatimu.', 'M9 19V6l12-2v13M9 19a3 3 0 11-6 0 3 3 0 016 0zm12-2a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['Search & Discover', 'Cari lagu, artis, dan album, lalu jelajahi rekomendasi harian serta lagu terpopuler bulan ini.', 'M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z'],
                ] as [$title, $desc, $icon])
                    <article class="rounded-2xl border border-[#ece8f6] bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-[#5b34f0]/10">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#c9a7fa] to-[#5b34f0] text-white shadow-md shadow-[#5b34f0]/30">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $icon }}" />
                            </svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-[#2b2b2f]">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-[#55555c]">{{ $desc }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-14 text-center">
                <a href="{{ route('register') }}"
                   class="inline-block rounded-full bg-gradient-to-r from-[#b78cf7] to-[#5b34f0] px-10 py-3 text-sm font-semibold text-white shadow-lg shadow-[#5b34f0]/30 transition hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#5b34f0]/40 focus:ring-offset-2">
                    Join Now
                </a>
            </div>
        </section>
    </main>

    {{-- ===== Footer ===== --}}
    <footer class="border-t border-[#ece8f6] bg-[#faf9fe]">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-6 py-8 text-sm text-[#55555c] sm:flex-row lg:px-10">
            <a href="{{ url('/landing') }}" class="flex items-center gap-2 font-semibold text-[#5b34f0]">
                <span class="h-5 w-5 rounded-full border-2 border-[#8b5cf6]"></span>
                OpenTune
            </a>
            <p>OpenTune &mdash; Your Music, Your Collection, Your Playlist.</p>
            <p>&copy; {{ date('Y') }} OpenTune. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
