<?php

namespace App\Policies;

use App\Models\Album;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AlbumPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Album $album): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Album $album): bool // <-- Ubah Song $song menjadi Album $album
    {
        return $user->id === $album->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Album $album): bool // <-- Ubah Song $song menjadi Album $album
    {
        return $this->update($user, $album);
    }

    public function restore(User $user, Album $album): bool
    {
        return false;
    }

    public function forceDelete(User $user, Album $album): bool
    {
        return false;
    }
}