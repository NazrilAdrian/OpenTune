<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class UserController extends Controller
{
    // Form register
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        // role SELALU 'user' untuk pendaftaran publik. Admin dibuat lewat seeder/admin panel.
        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->password, // di-hash otomatis oleh cast 'hashed'
            'role'     => 'user',
        ]);

        // Sesuai activity diagram: setelah register diarahkan ke halaman Login
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }

    // Form login
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        // Input boleh email atau username
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $request->login, 'password' => $request->password], $request->boolean('remember'))) {
            return back()
                ->withErrors(['login' => 'Email/username atau kata sandi salah.'])
                ->onlyInput('login');
        }

        $request->session()->regenerate(); // cegah session fixation

        // Admin diarahkan ke dashboard (milik Nazla) kalau route-nya sudah ada
        if ($request->user()->isAdmin() && Route::has('admin.dashboard')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}