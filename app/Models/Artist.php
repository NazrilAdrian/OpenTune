<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'bio', 'image'];

    public function songs()  { return $this->hasMany(Song::class); }
    public function albums() { return $this->hasMany(Album::class); }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : 'https://placehold.co/400x400/e9e5ff/5b36e8?text=Artist';
    }
}
