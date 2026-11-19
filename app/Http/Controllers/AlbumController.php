<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Models\{Album, Artist, Genre, Song};
use App\Support\AudioInfo;
use Illuminate\Support\Facades\{DB, Gate, Storage};
use Illuminate\Support\Str;
use ZipArchive;

class AlbumController extends Controller
{
    public function show(Album $album)
    {
        $album->load(['artist', 'songs.artist', 'songs.album']);
        return view('albums.show', compact('album'));
    }

    public function create()
    {
        return view('albums.create', [
            'genres'  => Genre::orderBy('name')->get(),
            'artists' => Artist::orderBy('name')->get(),
        ]);
    }

    public function store(AlbumRequest $request)
    {
        $disk   = Storage::disk('public');
        $stored = []; // path file yang sudah tersimpan, untuk dibersihkan kalau gagal

        try {
            $zip = new ZipArchive;
            if ($zip->open($request->file('zip')->getRealPath()) !== true) {
                return back()->withErrors(['zip' => 'File ZIP tidak dapat dibuka.'])->withInput();
            }

            // Guard sederhana: total ukuran setelah ekstrak maks 300 MB
            $total = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) $total += $zip->statIndex($i)['size'];
            if ($total > 300 * 1024 * 1024) {
                return back()->withErrors(['zip' => 'Isi ZIP terlalu besar.'])->withInput();
            }

            $artist = Artist::firstOrCreate(['name' => trim($request->artist_name)]);

            $coverPath = null;
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('albums/covers', 'public');
                $stored[]  = $coverPath;
            }

            DB::beginTransaction();

            $album = Album::create([
                'user_id'      => $request->user()->id,
                'artist_id'    => $artist->id,
                'name'         => $request->name,
                'cover_path'   => $coverPath,
                'release_date' => $request->release_date,
            ]);

            $count = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                $base  = basename($entry);              // basename -> aman dari path traversal (zip-slip)
                $ext   = strtolower(pathinfo($base, PATHINFO_EXTENSION));

                if (str_ends_with($entry, '/') || str_starts_with($entry, '__MACOSX')
                    || str_starts_with($base, '.') || ! in_array($ext, ['mp3', 'm4a'])) {
                    continue;
                }

                $stream = $zip->getStream($entry);
                if (! $stream) continue;

                $path = 'songs/audio/'.Str::uuid().'.'.$ext; // nama file kita yang tentukan
                $disk->put($path, $stream);
                fclose($stream);
                $stored[] = $path;

                // "01 - Judul Lagu.mp3" -> "Judul Lagu"
                $name  = pathinfo($base, PATHINFO_FILENAME);
                $title = trim(preg_replace('/^\d+\s*[-._]?\s*/', '', $name)) ?: $name;

                Song::create([
                    'user_id'   => $request->user()->id,
                    'artist_id' => $artist->id,
                    'album_id'  => $album->id,
                    'genre_id'  => $request->genre_id,
                    'title'     => Str::limit($title, 150, ''),
                    'file_path' => $path,
                    'duration'  => AudioInfo::duration($disk->path($path)),
                ]);
                $count++;
            }
            $zip->close();

            if ($count === 0) {
                throw new \RuntimeException('Tidak ada file MP3/M4A di dalam ZIP.');
            }

            DB::commit();

            return redirect()->route('albums.show', $album)
                ->with('success', "Album berhasil diupload ($count lagu).");
        } catch (\Throwable $e) {
            DB::rollBack();
            $disk->delete($stored);
            report($e);

            return back()->withErrors(['zip' => 'Upload gagal: '.$e->getMessage()])->withInput();
        }
    }

    public function edit(Album $album)
    {
        Gate::authorize('update', $album);

        return view('albums.edit', [
            'album'   => $album->load('artist'),
            'artists' => Artist::orderBy('name')->get(),
        ]);
    }

    public function update(AlbumRequest $request, Album $album)
    {
        Gate::authorize('update', $album);

        $artist = Artist::firstOrCreate(['name' => trim($request->artist_name)]);

        $data = [
            'artist_id'    => $artist->id,
            'name'         => $request->name,
            'release_date' => $request->release_date,
        ];

        if ($request->hasFile('cover')) {
            if ($album->cover_path) Storage::disk('public')->delete($album->cover_path);
            $data['cover_path'] = $request->file('cover')->store('albums/covers', 'public');
        }

        $album->update($data);

        return redirect()->route('albums.show', $album)->with('success', 'Album diperbarui.');
    }

    public function destroy(Album $album)
    {
        Gate::authorize('delete', $album);

        // Lagu di dalam album TIDAK ikut terhapus (album_id jadi NULL = single).
        if ($album->cover_path) Storage::disk('public')->delete($album->cover_path);
        $album->delete();

        return redirect()->route('songs.mine', ['tab' => 'album'])->with('success', 'Album dihapus.');
    }
}