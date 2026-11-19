<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Admin tidak boleh menghapus akunnya sendiri lewat panel admin.
    // Penghapusan akun sendiri lewat Account Settings (ProfileController), yang punya
    // pengaman "admin terakhir" dan konfirmasi password.
    public function delete(User $actor, User $target): bool
    {
        return $actor->isAdmin() && $actor->id !== $target->id;
    }

    // Admin tidak boleh mengubah role-nya sendiri. Karena hanya "diri sendiri" yang dikecualikan,
    // selalu ada minimal satu admin: orang yang sedang melakukan aksi.
    public function changeRole(User $actor, User $target): bool
    {
        return $actor->isAdmin() && $actor->id !== $target->id;
    }
}