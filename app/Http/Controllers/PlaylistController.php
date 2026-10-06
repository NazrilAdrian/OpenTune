<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaylistRequest;
use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PlaylistController extends Controller
{
    // My Playlist: hanya milik user yang sedang login
    public function index(Request $request): View
    {
        $playlists = $request->user()->playlists()
            ->withCount('songs')
            ->latest()
            ->paginate(12);

        return view('playlists.index', compact('playlists'));
    }

    public function create(): View
    {
        return view('playlists.create');
    }

    public function store(PlaylistRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'description']);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('playlists', 'public');
        }

        // user_id terisi otomatis dari relasi, bukan dari input form
        $playlist = $request->user()->playlists()->create($data);

        // Sesuai activity diagram: setelah valid -> buka playlist detail
        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Playlist berhasil dibuat. Tambahkan lagu pertamamu!');
    }

    // Playlist Detail (+ panel Add Song lewat parameter ?q=)
    public function show(Request $request, Playlist $playlist): View
    {
        Gate::authorize('view', $playlist);

        $songs = $playlist->songs()->with('artist')->get(); // sudah terurut by position

        // Pencarian lagu untuk ditambahkan (lagu yang sudah ada di playlist tidak ditampilkan)
        $q = trim((string) $request->query('q', ''));
        $results = collect();

        if ($q !== '') {
            $results = Song::with('artist')
                ->whereNotIn('id', $songs->pluck('id'))
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhereHas('artist', fn ($a) => $a->where('name', 'like', "%{$q}%"));
                })
                ->latest()
                ->take(10)
                ->get();
        }

        return view('playlists.show', compact('playlist', 'songs', 'q', 'results'));
    }

    public function edit(Playlist $playlist): View
    {
        Gate::authorize('update', $playlist);

        return view('playlists.edit', compact('playlist'));
    }

    public function update(PlaylistRequest $request, Playlist $playlist): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'description']);

        if ($request->hasFile('cover')) {
            $this->deleteCover($playlist);
            $data['cover'] = $request->file('cover')->store('playlists', 'public');
        } elseif ($request->boolean('remove_cover')) {
            $this->deleteCover($playlist);
            $data['cover'] = null;
        }

        $playlist->update($data);

        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Playlist berhasil diperbarui.');
    }

    public function destroy(Playlist $playlist): RedirectResponse
    {
        Gate::authorize('delete', $playlist);

        // File cover dibersihkan oleh event deleting di model Playlist,
        // baris playlist_songs ikut terhapus lewat cascade FK. Lagu aslinya TIDAK terhapus.
        $playlist->delete();

        return redirect()->route('playlists.index')->with('success', 'Playlist dihapus.');
    }

    // Putar playlist: antrean lagu berurutan sesuai position
    public function play(Playlist $playlist): View|RedirectResponse
    {
        Gate::authorize('view', $playlist);

        $songs = $playlist->songs()->with('artist')->get();

        if ($songs->isEmpty()) {
            return redirect()->route('playlists.show', $playlist)
                ->withErrors(['playlist' => 'Playlist masih kosong. Tambahkan lagu dulu.']);
        }

        $tracks = $songs->map(fn ($s) => [
            'id'     => $s->id,
            'title'  => $s->title,
            'artist' => $s->artist?->name ?? '-',
            'url'    => asset('storage/'.$s->file_path),
            'cover'  => $s->cover_path
                ? asset('storage/'.$s->cover_path)
                : 'https://placehold.co/300x300/c9a7f9/ffffff?text=%E2%99%AA',
        ])->values();

        return view('playlists.play', compact('playlist', 'tracks'));
    }

    private function deleteCover(Playlist $playlist): void
    {
        if ($playlist->cover) {
            Storage::disk('public')->delete($playlist->cover);
        }
    }
}