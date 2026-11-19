<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSongRequest;
use App\Http\Requests\ReorderSongRequest;
use App\Models\Playlist;
use App\Models\PlaylistSong;
use App\Models\Song;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PlaylistSongController extends Controller
{
    // Tambah lagu: position otomatis = posisi terakhir + 1
    public function store(AddSongRequest $request, Playlist $playlist): RedirectResponse
    {
        $next = (int) PlaylistSong::where('playlist_id', $playlist->id)->max('position') + 1;

        PlaylistSong::create([
            'playlist_id' => $playlist->id,
            'song_id'     => $request->song_id,
            'position'    => $next,
        ]);

        return back()->with('success', 'Lagu ditambahkan ke playlist.');
    }

    // Ubah posisi: tukar dengan lagu tetangganya (naik / turun)
    public function updatePosition(ReorderSongRequest $request, Playlist $playlist): RedirectResponse
    {
        $current = PlaylistSong::where('playlist_id', $playlist->id)
            ->where('song_id', $request->song_id)
            ->firstOrFail();

        // Cari tetangga berdasarkan urutan, bukan "position ± 1",
        // supaya tetap benar kalau ada celah (mis. karena lagu dihapus oleh pemiliknya).
        $neighbor = PlaylistSong::where('playlist_id', $playlist->id)
            ->when(
                $request->direction === 'up',
                fn ($q) => $q->where('position', '<', $current->position)->orderByDesc('position'),
                fn ($q) => $q->where('position', '>', $current->position)->orderBy('position'),
            )
            ->first();

        if ($neighbor) {
            DB::transaction(function () use ($current, $neighbor) {
                $a = $current->position;
                $b = $neighbor->position;

                $current->update(['position' => $b]);
                $neighbor->update(['position' => $a]);
            });
        }

        return back();
    }

    // Hapus lagu dari playlist (lagu aslinya tetap ada), lalu rapatkan posisi
    public function destroy(Playlist $playlist, Song $song): RedirectResponse
    {
        Gate::authorize('update', $playlist);

        DB::transaction(function () use ($playlist, $song) {
            $row = PlaylistSong::where('playlist_id', $playlist->id)
                ->where('song_id', $song->id)
                ->firstOrFail();

            $row->delete();

            PlaylistSong::where('playlist_id', $playlist->id)
                ->where('position', '>', $row->position)
                ->decrement('position');
        });

        return back()->with('success', 'Lagu dihapus dari playlist.');
    }
}