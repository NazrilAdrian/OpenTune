<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaylistSong extends Model
{
    // Tabel default untuk model ini sudah 'playlist_songs'
    protected $fillable = ['playlist_id', 'song_id', 'position'];

    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}