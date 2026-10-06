<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['user_id', 'artist_id', 'name', 'cover_path', 'release_date'];
    protected $casts = ['release_date' => 'date'];

    public function user()   { return $this->belongsTo(User::class); }
    public function artist() { return $this->belongsTo(Artist::class); }
    public function songs()  { return $this->hasMany(Song::class); }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_path
            ? asset('storage/'.$this->cover_path)
            : 'https://placehold.co/400x400/e9e5ff/5b36e8?text=Album';
    }
}
