<?php

namespace App\Http\Controllers;

use App\Http\Requests\SongRequest;
use App\Models\{Album, Artist, Genre, Song};
use App\Support\AudioInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Gate, Storage};

class SongController extends Controller
{
    // Home / Explore
    public function home()
    {
        $recommendations = Song::with(['artist', 'album'])->inRandomOrder()->take(6)->get();
        $latest          = Song::with(['artist', 'album'])->latest()->take(6)->get();
        $mostListened    = Song::with(['artist', 'album'])->inRandomOrder()->take(6)->get(); // sementara, lihat Fase 9 (opsional play_count)

        return view('songs.home', compact('recommendations', 'latest', 'mostListened'));
    }

    // Search: lagu + album
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $songs = Song::with(['artist', 'album'])
            ->where(fn ($query) => $query
                ->where('title', 'like', "%{$q}%")
                ->orWhereHas('artist', fn ($a) => $a->where('name', 'like', "%{$q}%")))
            ->take(24)->get();

        $albums = Album::with('artist')
            ->where(fn ($query) => $query
                ->where('name', 'like', "%{$q}%")
                ->orWhereHas('artist', fn ($a) => $a->where('name', 'like', "%{$q}%")))
            ->take(24)->get();

        return view('songs.search', compact('q', 'songs', 'albums'));
    }

    // Your Music / Manage (lagu & album milik user)
    public function mine(Request $request)
    {
        $tab    = $request->query('tab', 'song');
        $songs  = $request->user()->songs()->with(['artist', 'album'])->latest()->get();
        $albums = $request->user()->albums()->with('artist')->latest()->get();

        return view('songs.mine', compact('tab', 'songs', 'albums'));
    }

    public function show(Song $song)
    {
        $song->load(['artist', 'album', 'genre', 'user']);
        return view('songs.show', compact('song'));
    }

    public function create()
    {
        return view('songs.create', [
            'genres'  => Genre::orderBy('name')->get(),
            'artists' => Artist::orderBy('name')->get(),
            'albums'  => auth()->user()->albums()->orderBy('name')->get(),
        ]);
    }

    public function store(SongRequest $request)
    {
        // Sub-proses "Add Artist": pakai yang ada, kalau belum ada otomatis dibuat
        $artist = Artist::firstOrCreate(['name' => trim($request->artist_name)]);

        $audioPath = $request->file('audio')->store('songs/audio', 'public');
        $coverPath = $request->hasFile('cover')
            ? $request->file('cover')->store('songs/covers', 'public')
            : null;

        $song = Song::create([
            'user_id'    => $request->user()->id,
            'artist_id'  => $artist->id,
            'album_id'   => $request->album_id,
            'genre_id'   => $request->genre_id,
            'title'      => $request->title,
            'file_path'  => $audioPath,
            'cover_path' => $coverPath,
            'duration'   => AudioInfo::duration(Storage::disk('public')->path($audioPath)),
        ]);

        return redirect()->route('songs.show', $song)->with('success', 'Lagu berhasil diupload.');
    }

    public function edit(Song $song)
    {
        Gate::authorize('update', $song);

        return view('songs.edit', [
            'song'    => $song->load('artist'),
            'genres'  => Genre::orderBy('name')->get(),
            'artists' => Artist::orderBy('name')->get(),
            'albums'  => $song->user->albums()->orderBy('name')->get(),
        ]);
    }

    public function update(SongRequest $request, Song $song)
    {
        Gate::authorize('update', $song);

        $artist = Artist::firstOrCreate(['name' => trim($request->artist_name)]);

        $data = [
            'artist_id' => $artist->id,
            'album_id'  => $request->album_id,
            'genre_id'  => $request->genre_id,
            'title'     => $request->title,
        ];

        if ($request->hasFile('audio')) {
            Storage::disk('public')->delete($song->file_path);
            $data['file_path'] = $request->file('audio')->store('songs/audio', 'public');
            $data['duration']  = AudioInfo::duration(Storage::disk('public')->path($data['file_path']));
        }

        if ($request->hasFile('cover')) {
            if ($song->cover_path) Storage::disk('public')->delete($song->cover_path);
            $data['cover_path'] = $request->file('cover')->store('songs/covers', 'public');
        }

        $song->update($data);

        return redirect()->route('songs.show', $song)->with('success', 'Informasi lagu diperbarui.');
    }

    public function destroy(Song $song)
    {
        Gate::authorize('delete', $song);

        Storage::disk('public')->delete(array_filter([$song->file_path, $song->cover_path]));
        $song->delete();

        return redirect()->route('songs.mine')->with('success', 'Lagu dihapus.');
    }
}