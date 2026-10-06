<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Halaman profil (read-only) milik user yang sedang login
    public function show(Request $request): View
    {
        $user = $request->user();

        $songs  = $user->songs()->with(['artist', 'album'])->latest()->take(6)->get();
        $albums = $user->albums()->with('artist')->latest()->take(6)->get();

        // "Top artist" = artist dengan lagu terbanyak dari upload user ini.
        // (Versi "bulan ini / recently played" butuh tabel log pemutaran yang belum ada di ERD.)
        $topArtists = Artist::whereHas('songs', fn ($q) => $q->where('user_id', $user->id))
            ->withCount(['songs' => fn ($q) => $q->where('user_id', $user->id)])
            ->orderByDesc('songs_count')
            ->take(6)
            ->get();

        // relasi playlists() baru ada setelah modul Rafli ter-merge
        $playlists = method_exists($user, 'playlists')
            ? $user->playlists()->latest()->take(6)->get()
            : collect();

        return view('profile.show', compact('user', 'songs', 'albums', 'topArtists', 'playlists'));
    }

    // Halaman Edit Profile & Account Settings
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->safe()->only(['username', 'email']);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    // Hapus akun sendiri (wajib konfirmasi password)
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Jangan sampai sistem tidak punya admin sama sekali
        if ($user->isAdmin() && User::where('role', 'admin')->count() === 1) {
            return back()->withErrors(['password' => 'Admin terakhir tidak dapat dihapus.'], 'userDeletion');
        }

        Auth::logout();
        $user->delete(); // file foto/lagu/album ikut dibersihkan oleh event deleting di model User

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Akun kamu telah dihapus.');
    }
}