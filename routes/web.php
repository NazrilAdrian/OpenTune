<?php

use App\Http\Controllers\{UserController, ProfileController};
use App\Http\Controllers\{SongController, AlbumController, ArtistController};

// ===== MODUL USER & PROFILE (Nazril) =====
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'create'])->name('register');
    Route::post('/register', [UserController::class, 'store'])->name('register.store');

    Route::get('/login', [UserController::class, 'showLogin'])->name('login');   // nama 'login' WAJIB persis
    Route::post('/login', [UserController::class, 'login'])
        ->middleware('throttle:5,1')                                              // maks 5 percobaan / menit
        ->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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