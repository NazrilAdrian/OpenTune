<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function songs(): HasMany { return $this->hasMany(Song::class); }
    public function albums(): HasMany { return $this->hasMany(Album::class); }
    // playlists() akan ditambahkan setelah model Playlist (Rafli) ter-merge:
    public function playlists(): HasMany { return $this->hasMany(Playlist::class); }

    // ===== Helper =====
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profile_picture && Storage::disk('public')->exists($this->profile_picture)) {
            return asset('storage/'.$this->profile_picture);
        }

        // Fallback: avatar inisial sebagai SVG data URI (tidak butuh internet)
        $initial  = strtoupper(mb_substr($this->username ?? 'U', 0, 1));
        $svg      = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">' .
                    '<rect width="100" height="100" fill="#c9a7f9"/>' .
                    '<text x="50" y="50" dy=".35em" text-anchor="middle" font-size="48" font-family="sans-serif" fill="#ffffff">' .
                    htmlspecialchars($initial) . '</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
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