<?php

use App\Http\Controllers\AdminCatalogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSongController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\PlaylistSongController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\UserController;

// ===== MODUL USER & PROFILE (Nazril) =====
// '/' → Landing Page (publik, bisa diakses siapa saja)
Route::get('/', fn () => view('welcome'))->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'create'])->name('register');
    Route::post('/register', [UserController::class, 'store'])->name('register.store');

    Route::get('/login', [UserController::class, 'showLogin'])->name('login');   // nama 'login' WAJIB persis
    Route::post('/login', [UserController::class, 'login'])
        ->middleware('throttle:5,1')                                              // maks 5 percobaan / menit
        ->name('login.attempt');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('password.update');

    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
        ->middleware('throttle:10,1')
        ->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->middleware('throttle:10,1')
        ->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===== MODUL SONG / ARTIST / ALBUM (Syahid) =====
// '/home' → Dashboard utama, wajib login
Route::middleware('auth')->get('/home', [SongController::class, 'home'])->name('home');
Route::get('/search', [SongController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/my-music', [SongController::class, 'mine'])->name('songs.mine');

    // create/store/edit/update/destroy (show dibuat terpisah di bawah)
    Route::resource('songs', SongController::class)->except(['index', 'show']);
    Route::resource('albums', AlbumController::class)->except(['index', 'show']);
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
Route::get('/songs/{song}', [SongController::class,   'show'])->name('songs.show');
Route::get('/albums/{album}', [AlbumController::class,  'show'])->name('albums.show');
Route::get('/artists/{artist}', [ArtistController::class, 'show'])->name('artists.show');

// ===== MODUL ADMIN & GENRE (Nazla) =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');   // nama WAJIB persis 'admin.dashboard'

    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::resource('genres', GenreController::class)->except(['show']);

    Route::get('/songs', [AdminSongController::class, 'index'])->name('songs.index');

    Route::get('/songs/{song}/edit', [AdminSongController::class, 'edit'])->name('songs.edit');

    Route::put('/songs/{song}', [AdminSongController::class, 'update'])->name('songs.update');

    Route::patch('/songs/{song}', [AdminSongController::class, 'update']);

    Route::delete('/songs/{song}', [AdminSongController::class, 'destroy'])->name('songs.destroy');

    Route::resource('albums', AdminCatalogController::class)->except(['show']);
    Route::resource('artists', AdminCatalogController::class)->except(['show']);

    Route::delete('/albums/{album}', [AdminCatalogController::class, 'destroyAlbum'])->name('albums.destroy');
    Route::delete('/artists/{artist}', [AdminCatalogController::class, 'destroyArtist'])->name('artists.destroy');
});
