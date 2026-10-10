<?php

use App\Http\Controllers\{UserController, ProfileController};
use App\Http\Controllers\{SongController, AlbumController, ArtistController};
use App\Http\Controllers\{PlaylistController, PlaylistSongController};
use App\Http\Controllers\{AdminController, AdminSongController, AdminUserController, GenreController, AdminCatalogController};


// ===== MODUL USER & PROFILE (Nazril) =====
// Landing page (path '/' sudah dipakai route 'home' milik modul Song, jadi memakai '/landing')
Route::get('/landing', fn () => view('welcome'))->name('landing');

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
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
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

// ===== MODUL PLAYLIST (Rafli) =====
Route::middleware('auth')->group(function () {
    Route::resource('playlists', PlaylistController::class);
    Route::get('/playlists/{playlist}/play', [PlaylistController::class, 'play'])->name('playlists.play');

    Route::post('/playlists/{playlist}/songs', [PlaylistSongController::class, 'store'])->name('playlists.songs.store');
    Route::patch('/playlists/{playlist}/songs/reorder', [PlaylistSongController::class, 'updatePosition'])->name('playlists.songs.reorder');
    Route::delete('/playlists/{playlist}/songs/{song}', [PlaylistSongController::class, 'destroy'])->name('playlists.songs.destroy');
});

// Publik (didefinisikan SETELAH group agar /songs/create tidak tertangkap {song})
Route::get('/songs/{song}',     [SongController::class,   'show'])->name('songs.show');
Route::get('/albums/{album}',   [AlbumController::class,  'show'])->name('albums.show');
Route::get('/artists/{artist}', [ArtistController::class, 'show'])->name('artists.show');


// ===== MODUL ADMIN & GENRE (Nazla) =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');   // nama WAJIB persis 'admin.dashboard'

    Route::resource('users',  AdminUserController::class)->except(['show']);
    Route::resource('genres', GenreController::class)->except(['show']);

    Route::get('/songs', [AdminSongController::class, 'index'])->name('songs.index');
    Route::delete('/songs/{song}', [AdminSongController::class, 'destroy'])->name('songs.destroy');

    Route::get('/albums', [AdminCatalogController::class, 'albums'])->name('albums.index');

    Route::get('/artists', [AdminCatalogController::class, 'artists'])->name('artists.index');
});