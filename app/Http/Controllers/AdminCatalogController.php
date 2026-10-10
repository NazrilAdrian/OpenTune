<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCatalogController extends Controller
{
    public function albums(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $albums = Album::with(['artist', 'user'])->withCount('songs')
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%")
                ->orWhereHas('artist', fn ($artist) => $artist->where('name', 'like', "%{$q}%")))
            ->latest()->paginate(18)->withQueryString();

        return view('admin.albums.index', compact('albums', 'q'));
    }

    public function artists(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $artists = Artist::withCount(['songs', 'albums'])
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')->paginate(18)->withQueryString();

        return view('admin.artists.index', compact('artists', 'q'));
    }
}
