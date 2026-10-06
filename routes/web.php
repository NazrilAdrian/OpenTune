<?php

use App\Http\Controllers\{SongController, AlbumController, ArtistController};

// ===== MODUL SONG / ARTIST / ALBUM (Syahid) =====
Route::get('/', [SongController::class, 'home'])->name('home');
Route::get('/search', [SongController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/my-music', [SongController::class, 'mine'])->name('songs.mine');

    // create/store/edit/update/destroy (show dibuat terpisah di bawah)
    Route::resource('songs',   SongController::class)->except(['index', 'show']);
    Route::resource('albums',  AlbumController::class)->except(['index', 'show']);
    Route::resource('artists', ArtistController::class)->except(['index', 'show']);
});

// Publik (didefinisikan SETELAH group agar /songs/create tidak tertangkap {song})
Route::get('/songs/{song}',     [SongController::class,   'show'])->name('songs.show');
Route::get('/albums/{album}',   [AlbumController::class,  'show'])->name('albums.show');
Route::get('/artists/{artist}', [ArtistController::class, 'show'])->name('artists.show');