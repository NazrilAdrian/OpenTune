<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // PERHATIAN: jangan pernah memakai User::create($request->all()).
    // Selalu pilih field secara eksplisit agar 'role' tidak bisa diisi dari form.
    protected $fillable = ['username', 'email', 'password', 'profile_picture', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // ===== Relasi =====
    // songs() & albums() ditambahkan Syahid (Modul 2) -> jangan duplikat.
    public function songs() { return $this->hasMany(Song::class); }
    public function albums() { return $this->hasMany(Album::class); }
    // playlists() akan ditambahkan setelah model Playlist (Rafli) ter-merge:
    public function playlists() { return $this->hasMany(Playlist::class); }

    // ===== Helper =====
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profile_picture) {
            return asset('storage/'.$this->profile_picture);
        }

        $initial = urlencode(strtoupper(mb_substr($this->username ?? 'U', 0, 1)));
        return "https://placehold.co/300x300/c9a7f9/ffffff?text={$initial}";
    }
    
    // Saat user dihapus (hapus akun sendiri ATAU dihapus admin), bersihkan file fisiknya.
    // Baris di database ikut terhapus lewat cascade FK, tetapi FILE di storage tidak.
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $paths = [$user->profile_picture];

            foreach ($user->songs as $song) {
                $paths[] = $song->file_path;
                $paths[] = $song->cover_path;
            }
            foreach ($user->albums as $album) {
                $paths[] = $album->cover_path;
            }
            foreach ($user->playlists as $playlist) {
                $paths[] = $playlist->cover;
            }
            // TODO (koordinasi dgn Rafli): tambahkan cover playlist milik user di sini.

            Storage::disk('public')->delete(array_filter($paths));
        });
    }
}