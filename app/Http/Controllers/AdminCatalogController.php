<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminCatalogController extends Controller
{
    public function index(Request $request)
    {
        return $request->routeIs('admin.albums.index')
            ? $this->albums($request)
            : $this->artists($request);
    }

    public function albums(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $albums = Album::with(['artist', 'user'])->withCount('songs')
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhereHas('artist', fn ($a) => $a->where('name', 'like', "%{$q}%"));
            }))
            ->latest()->paginate(10)->withQueryString();
        return view('admin.albums.index', compact('albums', 'q'));
    }

    public function artists(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $artists = Artist::withCount(['songs', 'albums'])
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')->paginate(10)->withQueryString();
        return view('admin.artists.index', compact('artists', 'q'));
    }

    public function create()
    {
        $type = request()->route()->getName();
        if (str_contains($type, 'albums')) {
            return view('admin.albums.create', ['artists' => Artist::orderBy('name')->get()]);
        }
        return view('admin.artists.create');
    }

    public function store(Request $request)
    {
        if ($request->routeIs('admin.albums.store')) {
            $data = $request->validate([
                'name' => ['required','string','max:150'],
                'artist_id' => ['required','exists:artists,id'],
                'release_date' => ['nullable','date'],
                'cover' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            ]);
            $coverPath = $request->file('cover')?->store('albums/covers', 'public');
            Album::create([
                'user_id' => $request->user()->id,
                'artist_id' => $data['artist_id'],
                'name' => $data['name'],
                'release_date' => $data['release_date'] ?? null,
                'cover_path' => $coverPath,
            ]);
            return redirect()->route('admin.albums.index')->with('success', 'Album berhasil ditambahkan.');
        }

        $data = $request->validate([
            'name' => ['required','string','max:100', 'unique:artists,name'],
            'bio' => ['nullable','string','max:2000'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);
        if ($request->hasFile('image')) $data['image'] = $request->file('image')->store('artists', 'public');
        Artist::create($data);
        return redirect()->route('admin.artists.index')->with('success', 'Artist berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        if ($request->routeIs('admin.albums.edit')) {
            $album = Album::findOrFail($id);
            return view('admin.albums.edit', ['album' => $album, 'artists' => Artist::orderBy('name')->get()]);
        }
        return view('admin.artists.edit', ['artist' => Artist::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        if ($request->routeIs('admin.albums.update')) {
            $album = Album::findOrFail($id);
            $data = $request->validate([
                'name' => ['required','string','max:150'],
                'artist_id' => ['required','exists:artists,id'],
                'release_date' => ['nullable','date'],
                'cover' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            ]);
            if ($request->hasFile('cover')) {
                if ($album->cover_path) Storage::disk('public')->delete($album->cover_path);
                $data['cover_path'] = $request->file('cover')->store('albums/covers', 'public');
            }
            unset($data['cover']);
            $album->update($data);
            return redirect()->route('admin.albums.index')->with('success', 'Album berhasil diperbarui.');
        }

        $artist = Artist::findOrFail($id);
        $data = $request->validate([
            'name' => ['required','string','max:100', Rule::unique('artists','name')->ignore($artist->id)],
            'bio' => ['nullable','string','max:2000'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);
        if ($request->hasFile('image')) {
            if ($artist->image) Storage::disk('public')->delete($artist->image);
            $data['image'] = $request->file('image')->store('artists', 'public');
        }
        $artist->update($data);
        return redirect()->route('admin.artists.index')->with('success', 'Artist berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->routeIs('admin.albums.destroy')) {
            $album = Album::findOrFail($id);
            if ($album->songs()->exists()) return back()->withErrors(['album' => 'Album masih memiliki lagu. Hapus atau pindahkan lagu terlebih dahulu.']);
            if ($album->cover_path) Storage::disk('public')->delete($album->cover_path);
            $album->delete();
            return redirect()->route('admin.albums.index')->with('success', 'Album berhasil dihapus.');
        }
        $artist = Artist::findOrFail($id);
        if ($artist->songs()->exists() || $artist->albums()->exists()) {
            return back()->withErrors(['artist' => 'Artist masih memiliki lagu atau album, sehingga tidak bisa dihapus.']);
        }
        if ($artist->image) Storage::disk('public')->delete($artist->image);
        $artist->delete();
        return redirect()->route('admin.artists.index')->with('success', 'Artist berhasil dihapus.');
    }
}
