<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin OpenTune')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:Inter,ui-sans-serif,system-ui,sans-serif;background:#f7f8fc;color:#24232b}
        .admin-sidebar{width:245px}.admin-main{margin-left:245px}
        .admin-nav{display:flex;align-items:center;gap:12px;padding:11px 14px;border-radius:10px;color:#696776;font-size:14px}
        .admin-nav:hover,.admin-nav.active{color:#fff;background:linear-gradient(100deg,#c18aff,#6537e9)}
        .admin-card{background:#fff;border:1px solid #eeedf3;border-radius:14px;box-shadow:0 3px 14px #29205b05}
        .admin-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;border-radius:9px;padding:9px 16px;background:linear-gradient(100deg,#bd85fa,#6537e9);color:#fff;font-size:13px;font-weight:600}
        .admin-input{width:100%;border:1px solid #e5e3ed;border-radius:9px;padding:10px 12px;font-size:13px;outline:none;background:white}
        .admin-input:focus{border-color:#8b5cf6;box-shadow:0 0 0 3px #8b5cf61a}
        .admin-table{width:100%;border-collapse:collapse;font-size:13px}.admin-table th{text-align:left;color:#8b8997;font-weight:500;background:#fafaff}
        .admin-table th,.admin-table td{padding:13px 15px;border-bottom:1px solid #f0eff5}
        .admin-pill{display:inline-block;padding:4px 9px;border-radius:20px;font-size:11px;font-weight:600}
        @media(max-width:900px){.admin-sidebar{width:72px}.admin-sidebar .nav-label,.admin-sidebar .brand-label{display:none}.admin-main{margin-left:72px}.admin-sidebar .admin-nav{justify-content:center;padding:12px 5px}}
        @media(max-width:600px){.admin-sidebar{display:none}.admin-main{margin-left:0}.admin-table-wrap{overflow-x:auto}}
    </style>
</head>
<body>
<aside class="admin-sidebar fixed inset-y-0 left-0 z-30 border-r border-[#eceaf2] bg-white px-4 py-6">
    <a href="{{ route('admin.dashboard') }}" class="mb-9 flex items-center gap-2 px-1">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#c18aff] to-[#6537e9] text-white font-bold">OT</span>
        <span class="brand-label text-lg font-bold text-[#5d35df]">OT.Admin</span>
    </a>
    <nav class="space-y-2">
        <a class="admin-nav {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><span>▦</span><span class="nav-label">Dashboard</span></a>
        <a class="admin-nav {{ request()->routeIs('admin.songs.*') ? 'active' : '' }}" href="{{ route('admin.songs.index') }}"><span>♫</span><span class="nav-label">Song</span></a>
        <a class="admin-nav {{ request()->routeIs('admin.albums.*') ? 'active' : '' }}" href="{{ route('admin.albums.index') }}"><span>▣</span><span class="nav-label">Album</span></a>
        <a class="admin-nav {{ request()->routeIs('admin.artists.*') ? 'active' : '' }}" href="{{ route('admin.artists.index') }}"><span>◉</span><span class="nav-label">Artist</span></a>
        <a class="admin-nav {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}" href="{{ route('admin.genres.index') }}"><span>♬</span><span class="nav-label">Genre</span></a>
        <a class="admin-nav {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><span>♙</span><span class="nav-label">User Management</span></a>
    </nav>
    <div class="absolute bottom-5 left-4 right-4 border-t border-gray-100 pt-4">
        <a href="{{ route('home') }}" class="admin-nav"><span>↗</span><span class="nav-label">Kembali ke website</span></a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="admin-nav w-full text-left"><span>⇥</span><span class="nav-label">Logout</span></button></form>
    </div>
</aside>
<div class="admin-main min-h-screen">
    <header class="sticky top-0 z-20 flex h-[72px] items-center justify-between border-b border-[#eceaf2] bg-white/95 px-5 sm:px-8">
        <div class="text-sm text-gray-400">OpenTune <span class="mx-2">/</span><span class="font-semibold text-gray-700">@yield('heading','Dashboard')</span></div>
        <div class="flex items-center gap-3"><div class="text-right"><p class="text-sm font-semibold">{{ auth()->user()->username ?? 'Admin' }}</p><p class="text-xs text-gray-400">Administrator</p></div><img class="h-9 w-9 rounded-full object-cover" src="{{ auth()->user()->profile_picture_url ?? 'https://placehold.co/80x80/c9a7f9/ffffff?text=A' }}" alt="Foto admin"></div>
    </header>
    <main class="p-5 sm:p-8">
        @if(session('success'))<div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
    </main>
</div>
</body>
</html>
