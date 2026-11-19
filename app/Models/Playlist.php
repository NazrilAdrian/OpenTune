<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Playlist extends Model
{
    // user_id sengaja TIDAK fillable: selalu diisi lewat $user->playlists()->create()
    protected $fillable = ['name', 'description', 'cover'];

    // ===== Relasi =====
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Baca-saja: daftar lagu, sudah terurut menurut position.
    // Nama tabel pivot harus ditulis eksplisit karena default Laravel adalah 'playlist_song' (tunggal).
    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'playlist_songs')
            ->withPivot('id', 'position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    // Dipakai untuk menulis (tambah / geser / hapus baris pivot)
    public function playlistSongs(): HasMany
    {
        return $this->hasMany(PlaylistSong::class);
    }

    // ===== Helper =====
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover) {
            return asset('storage/'.$this->cover);
        }

        $initial = urlencode(strtoupper(mb_substr($this->name ?? 'P', 0, 1)));
        return "https://placehold.co/300x300/c9a7f9/ffffff?text={$initial}";
    }

    // Hapus file cover saat playlist dihapus lewat $playlist->delete().
    // (Kalau yang dihapus adalah akun pemiliknya, event ini TIDAK jalan; itu ditangani di User::booted(), lihat 2.3.)
    protected static function booted(): void
    {
        static::deleting(function (Playlist $playlist) {
            if ($playlist->cover) {
                Storage::disk('public')->delete($playlist->cover);
            }
        });
    }
}