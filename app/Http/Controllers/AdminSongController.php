<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Song;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Artist;
use App\Models\Album;

class AdminSongController extends Controller
{
    // Menampilkan daftar lagu, pencarian, dan filter genre
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $genreId = $request->query('genre_id');

        $songs = Song::with(['artist', 'genre', 'user', 'album'])
            ->when($q !== '', fn ($query) => $query->where(
                fn ($w) => $w
                    ->where('title', 'like', "%{$q}%")
                    ->orWhereHas('artist', fn ($a) => $a->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('user', fn ($u) => $u->where('username', 'like', "%{$q}%"))
            ))
            ->when($genreId, fn ($query) => $query->where('genre_id', $genreId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $genres = Genre::orderBy('name')->get();

        return view('admin.songs.index', compact(
            'songs',
            'genres',
            'q',
            'genreId'
        ));
    }

    // Menampilkan halaman edit khusus admin
    public function edit(Song $song): View
    {
        $song->load(['artist', 'album', 'genre', 'user']);

        Gate::authorize('update', $song);

        $artists = Artist::orderBy('name')->get();
        $albums = Album::all();
        $genres = Genre::orderBy('name')->get();

        return view('admin.songs.edit', compact(
            'song',
            'artists',
            'albums',
            'genres'
        ));
    }

    // Update lagu akan ditambahkan setelah nama field pada form dipastikan

    // Menghapus lagu sebagai moderasi
    public function destroy(Request $request, Song $song): RedirectResponse
    {
        Gate::authorize('delete', $song);

        Storage::disk('public')->delete(
            array_filter([$song->file_path, $song->cover_path])
        );

        $song->delete();

        return redirect()
            ->route('admin.songs.index', $request->only(['q', 'genre_id']))
            ->with('success', 'Lagu dihapus.');
    }
}