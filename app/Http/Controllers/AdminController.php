<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'users'     => User::count(),
            'admins'    => User::where('role', 'admin')->count(),
            'songs'     => Song::count(),
            'albums'    => Album::count(),
            'artists'   => Artist::count(),
            'genres'    => Genre::count(),
            'playlists' => Playlist::count(),
        ];

        $latestUsers = User::latest()->take(5)->get();
        $latestSongs = Song::with(['artist', 'user'])->latest()->take(5)->get();
        $topGenres   = Genre::withCount('songs')->orderByDesc('songs_count')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestUsers', 'latestSongs', 'topGenres'));
    }
}