<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $genres = Genre::withCount('songs')
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.genres.index', compact('genres', 'q'));
    }

    public function create(): View
    {
        return view('admin.genres.create');
    }

    public function store(GenreRequest $request): RedirectResponse
    {
        Genre::create($request->validated());

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil ditambahkan.');
    }

    public function edit(Genre $genre): View
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(GenreRequest $request, Genre $genre): RedirectResponse
    {
        $genre->update($request->validated());

        return redirect()->route('admin.genres.index')->with('success', 'Genre diperbarui.');
    }

    public function destroy(Genre $genre): RedirectResponse
    {
        // songs.genre_id memakai restrictOnDelete (migration Syahid), jadi genre yang masih
        // dipakai akan membuat database menolak. Cek dulu supaya pesannya ramah.
        $used = $genre->songs()->count();

        if ($used > 0) {
            return back()->withErrors([
                'genre' => "Genre \"{$genre->name}\" masih dipakai {$used} lagu dan tidak bisa dihapus.",
            ]);
        }

        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre dihapus.');
    }
}