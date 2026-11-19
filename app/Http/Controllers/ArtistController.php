<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    public function show(Artist $artist)
    {
        $artist->load(['albums', 'songs.album']);
        return view('artists.show', compact('artist'));
    }

    // Semua user login boleh menambah artist (use case "Add Artist")
    public function create() { return view('artists.create'); }

    public function store(ArtistRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('artists', 'public');
        }
        $artist = Artist::create($data);

        return redirect()->route('artists.show', $artist)->with('success', 'Artist ditambahkan.');
    }

    // Edit & hapus artist hanya admin (use case "Artist" -> Admin)
    public function edit(Artist $artist)
    {
        $this->adminOnly();
        return view('artists.edit', compact('artist'));
    }

    public function update(ArtistRequest $request, Artist $artist)
    {
        $this->adminOnly();

        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($artist->image) Storage::disk('public')->delete($artist->image);
            $data['image'] = $request->file('image')->store('artists', 'public');
        }
        $artist->update($data);

        return redirect()->route('artists.show', $artist)->with('success', 'Artist diperbarui.');
    }

    public function destroy(Artist $artist)
    {
        $this->adminOnly();

        if ($artist->songs()->exists() || $artist->albums()->exists()) {
            return back()->withErrors(['artist' => 'Artist masih memiliki lagu/album, tidak bisa dihapus.']);
        }
        if ($artist->image) Storage::disk('public')->delete($artist->image);
        $artist->delete();

        return redirect()->route('home')->with('success', 'Artist dihapus.');
    }

    private function adminOnly(): void
    {
        abort_unless(auth()->user()?->role === 'admin', 403);
    }
}