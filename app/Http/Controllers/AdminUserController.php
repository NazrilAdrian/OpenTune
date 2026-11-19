<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    // Lihat user: cari username/email, filter role
    public function index(Request $request): View
    {
        $q    = trim((string) $request->query('q', ''));
        $role = $request->query('role');

        $users = User::query()
            ->withCount(['songs', 'albums', 'playlists'])
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('username', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")))
            ->when(in_array($role, ['user', 'admin'], true), fn ($query) => $query->where('role', $role))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q', 'role'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    // Tambah user (role dipilih admin)
    public function store(AdminUserRequest $request): RedirectResponse
    {
        // Field dipilih satu per satu, bukan $request->all()
        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->password, // di-hash otomatis oleh cast 'hashed'
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    // Atur user: username, email, role, dan reset password
    public function update(AdminUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->safe()->only(['username', 'email']);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        // Role akun sendiri tidak boleh diubah; input role diabaikan diam-diam kalau ini akun sendiri
        if ($request->filled('role') && $request->user()->can('changeRole', $user)) {
            $data['role'] = $request->role;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user diperbarui.');
    }

    // Hapus user
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user); // 403 kalau menghapus diri sendiri

        $name = $user->username;

        // WAJIB $user->delete(), bukan User::where(...)->delete().
        // Event 'deleting' di User::booted() yang membersihkan file foto, lagu, cover, dan cover playlist.
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} beserta lagu, album, dan playlist miliknya dihapus.");
    }
}