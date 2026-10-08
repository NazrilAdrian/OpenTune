<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OpenTune')</title>
    @vite(['resources/css/modul2.css', 'resources/js/modul2.js'])
</head>
<body class="min-h-screen bg-[#f7f9fc] text-[#2f3035] antialiased font-sans">
    @if(!($minimal ?? false))
        @include('components.sidebar')
        @include('components.topbar')
    @endif

    <main class="{{ ($minimal ?? false) ? 'min-h-screen px-4 py-8 sm:px-8' : 'ml-0 lg:ml-[274px] min-h-screen px-4 pb-32 pt-28 sm:px-6 lg:px-10' }}">
        @include('components.flash')
        @yield('content')
    </main>

    @if(!($minimal ?? false))
        @include('components.music-player')
    @endif
</body>
</html>