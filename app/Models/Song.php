<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'user_id', 'artist_id', 'album_id', 'genre_id',
        'title', 'file_path', 'cover_path', 'duration',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function artist() { return $this->belongsTo(Artist::class); }
    public function album()  { return $this->belongsTo(Album::class); }
    public function genre()  { return $this->belongsTo(Genre::class); }
    public function playlistSongs() {  return $this->hasMany(PlaylistSong::class); }
    public function getAudioUrlAttribute(): string
    {
        return asset('storage/'.$this->file_path);
    }

    // cover lagu -> kalau kosong pakai cover album -> kalau kosong placeholder
    public function getCoverUrlAttribute(): string
    {
        $path = $this->cover_path ?? $this->album?->cover_path;
        return $path ? asset('storage/'.$path)
                     : 'https://placehold.co/400x400/e9e5ff/5b36e8?text=Song';
    }

    public function getDurationLabelAttribute(): string
    {
        return $this->duration ? gmdate('i:s', $this->duration) : '--:--';
    }
}
