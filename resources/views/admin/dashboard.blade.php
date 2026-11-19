@extends('admin.layout')
@section('title','Dashboard Admin | OpenTune')
@section('heading','Dashboard')
@section('content')
<div class="mb-6"><h1 class="text-2xl font-bold">Welcome Back, Admin!</h1><p class="mt-1 text-sm text-gray-500">Ringkasan aktivitas dan konten OpenTune.</p></div>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
@foreach([['Total User',$stats['users'],'♙'],['Total Lagu',$stats['songs'],'♫'],['Total Album',$stats['albums'],'▣'],['Total Artist',$stats['artists'],'◉']] as $stat)
<div class="admin-card flex items-center justify-between p-5"><div><p class="text-sm text-gray-500">{{ $stat[0] }}</p><p class="mt-2 text-2xl font-bold">{{ number_format($stat[1]) }}</p></div><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-2xl text-purple-600">{{ $stat[2] }}</span></div>
@endforeach
</div>
<div class="mt-6 grid grid-cols-1 gap-5 xl:grid-cols-3">
<section class="admin-card p-5 xl:col-span-2"><div class="mb-4 flex items-center justify-between"><h2 class="font-bold">Recently Uploaded Songs</h2><a class="text-sm font-semibold text-purple-600" href="{{ route('admin.songs.index') }}">See all</a></div>
<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Song</th><th>Artist</th><th>Uploaded by</th><th>Genre</th></tr></thead><tbody>
@forelse($latestSongs as $song)<tr><td><div class="flex items-center gap-3"><img class="h-9 w-9 rounded-lg object-cover" src="{{ $song->cover_url }}" alt=""><span class="font-semibold">{{ $song->title }}</span></div></td><td>{{ $song->artist?->name ?? '-' }}</td><td>{{ $song->user?->username ?? '-' }}</td><td>{{ $song->genre?->name ?? '-' }}</td></tr>@empty<tr><td colspan="4" class="py-8 text-center text-gray-400">Belum ada lagu.</td></tr>@endforelse
</tbody></table></div></section>
<section class="admin-card p-5"><div class="mb-4 flex items-center justify-between"><h2 class="font-bold">Recent Users</h2><a class="text-sm font-semibold text-purple-600" href="{{ route('admin.users.index') }}">See all</a></div>
<div class="space-y-4">@forelse($latestUsers as $user)<div class="flex items-center justify-between gap-3"><div class="min-w-0"><p class="truncate text-sm font-semibold">{{ $user->username }}</p><p class="truncate text-xs text-gray-400">{{ $user->email }}</p></div><span class="admin-pill {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($user->role) }}</span></div>@empty<p class="text-sm text-gray-400">Belum ada user.</p>@endforelse</div>
<div class="mt-6 border-t pt-4"><h3 class="mb-3 text-sm font-semibold">Top Genres</h3>@forelse($topGenres as $genre)<div class="mb-3 flex justify-between text-sm"><span>{{ $genre->name }}</span><span class="text-gray-500">{{ $genre->songs_count }} lagu</span></div>@empty<p class="text-xs text-gray-400">Belum ada genre.</p>@endforelse</div></section>
</div>
@endsection
