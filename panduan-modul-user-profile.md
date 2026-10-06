# Panduan Step-by-Step OpenTune — Modul User & Profile (Auth)

Penanggung jawab: **Nazril Adrian** · Branch: `feature/user`
Cakupan sesuai rancangan: Register, Login, Logout, Profile (lihat), Edit Profile, Account Settings (hapus akun).

> Asumsi: Laravel 11/12, PHP 8.2+, MySQL, dan **Modul 2 (Song/Artist/Album) sudah dikerjakan Syahid**. Panduan ini sengaja tidak mengulang apa yang sudah ada di panduan modul 2.

---

## Yang Sengaja TIDAK Diulang (sudah ada di Modul 2)

| Sudah ada di Modul 2 | Dipakai ulang di modul ini |
|---|---|
| `.env`, database `opentune`, `FILESYSTEM_DISK=public` | Tidak perlu diubah |
| `php artisan storage:link` | Foto profil disimpan di disk `public` yang sama |
| Naikkan limit upload `php.ini` (64 MB) | Foto profil hanya 2 MB, sudah aman |
| Tailwind CDN, font Poppins, class `.btn-grad` | Dipakai di layout `guest` (disalin ke file baru) |
| `layouts/app.blade.php` (sidebar, search, Upload, flash message, error banner, Music Player) | Halaman Profile & Edit Profile memakai layout ini; hanya **1 bagian kecil diubah** (Fase 6.7) |
| `components/song-card` & `album-card` | Dipakai di halaman Profile |
| Relasi `User::songs()` dan `User::albums()` | Sudah ditambahkan Syahid, **jangan ditulis dua kali** |
| Pola Policy, Form Request, upload file ke `Storage::disk('public')` | Pola yang sama dipakai di sini |
| Teknik commit per langkah & alur PR | Hanya perintah branch yang berbeda |

---

## Daftar Fase

| Fase | Isi | Hasil |
|---|---|---|
| 0 | Persiapan branch | Branch `feature/user` siap |
| 1 | Migration `users` + Factory + Seeder | Tabel `users` sesuai ERD, akun admin & user demo |
| 2 | Model `User` | Fillable, cast, accessor foto, pembersihan file saat user dihapus |
| 3 | Form Request | Validasi Register, Login, Update Profile |
| 4 | Controller | `UserController` (auth) dan `ProfileController` |
| 5 | Route | `routes/web.php` + `bootstrap/app.php` |
| 6 | Blade View | Login, Register, Profile, Edit Profile, (opsional) Landing |
| 7 | Testing | Checklist manual + 2 feature test |
| 8 | Git, PR, integrasi | Merge ke `main` |

**Saran penting (PRIORITAS):** modul ini adalah **fondasi** tim. Tabel `songs`, `albums`, dan `playlists` semua punya FK ke `users`, dan Policy Syahid memakai `users.role`. Karena itu **selesaikan Fase 1–2, lalu langsung buat Pull Request kecil (PR #1)** sebelum mengerjakan view dan controller. Jangan tunggu seluruh modul selesai.

---

## Fase 0 — Persiapan

```bash
git checkout main
git pull origin main
git checkout -b feature/user
```

Kalau branch `feature/song` Syahid sudah di-merge ke `main`, hasil `pull` di atas sudah membawa layout, komponen kartu, dan model musiknya. Kalau belum, panduan ini tetap bisa dikerjakan; bagian yang bergantung ke modul 2 diberi catatan.

---

## Fase 1 — Migration, Factory, Seeder

### 1.1 Ubah migration `users` bawaan Laravel

Jangan buat migration baru. Edit file bawaan `database/migrations/0001_01_01_000000_create_users_table.php` supaya **tetap berjalan paling awal** (sebelum `songs`, `albums`, `playlists` yang punya FK ke `users`).

Ganti **hanya** isi `Schema::create('users', ...)`. Biarkan blok `password_reset_tokens` dan `sessions` apa adanya.

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('username', 50)->unique();
    $table->string('email', 100)->unique();
    $table->string('password', 255);
    $table->string('profile_picture', 255)->nullable();
    $table->enum('role', ['user', 'admin'])->default('user');
    $table->rememberToken();   // wajib untuk fitur "Remember Me"
    $table->timestamps();
});
```

Catatan penyimpangan kecil dari ERD (kabari tim dan perbarui dokumen ERD):
- `username` dibuat **UNIQUE**. Di UI, login memakai "Email or Username", jadi username harus unik agar login tidak ambigu.
- Ada kolom `remember_token`, kebutuhan teknis Laravel untuk Remember Me.
- Kolom bawaan `name` dan `email_verified_at` dihapus karena tidak ada di ERD.

### 1.2 Jalankan ulang migration

Kalau sebelumnya sudah pernah `php artisan migrate` dengan migration bawaan:

```bash
php artisan migrate:fresh
```

> `migrate:fresh` menghapus semua tabel. Aman untuk database lokal. **Jangan** dijalankan di database bersama tim tanpa memberi tahu semua orang.

### 1.3 Perbarui `database/factories/UserFactory.php`

Factory bawaan masih memakai kolom `name`, sehingga akan error. Modul 2 juga memakai `User::factory()` di feature test-nya, jadi bagian ini wajib.

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'username'        => fake()->unique()->userName(),
            'email'           => fake()->unique()->safeEmail(),
            'password'        => static::$password ??= Hash::make('password'),
            'profile_picture' => null,
            'role'            => 'user',
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => 'admin']);
    }
}
```
Hapus method `unverified()` bawaan karena kolom `email_verified_at` sudah tidak ada.

### 1.4 Seeder akun awal

```bash
php artisan make:seeder UserSeeder
```
```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // password otomatis di-hash oleh cast 'hashed' di model User
        User::firstOrCreate(['email' => 'admin@opentune.test'], [
            'username' => 'admin',
            'password' => 'Admin123',
            'role'     => 'admin',
        ]);

        User::firstOrCreate(['email' => 'user@opentune.test'], [
            'username' => 'demo',
            'password' => 'User1234',
            'role'     => 'user',
        ]);
    }
}
```

Perbarui `database/seeders/DatabaseSeeder.php`. **Hapus** baris bawaan `User::factory()->create(['name' => 'Test User', ...])` karena kolom `name` sudah tidak ada, lalu panggil seeder:

```php
public function run(): void
{
    $this->call([
        UserSeeder::class,
        GenreSeeder::class,   // milik Syahid; kalau belum ter-merge, hapus baris ini dulu
    ]);
}
```
Jika `DatabaseSeeder` sudah diubah Syahid (ia menambah `GenreSeeder`), **gabungkan keduanya**, jangan saling menimpa.

```bash
php artisan migrate:fresh --seed
```

Akun hasil seeder: `admin@opentune.test` / `Admin123` dan `user@opentune.test` / `User1234`. Ganti password admin sebelum dipakai di luar lokal.

---

## Fase 2 — Model `User`

Edit `app/Models/User.php` (ganti seluruh isi, lalu **pertahankan** `songs()` dan `albums()` kalau Syahid sudah menambahkannya):

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // PERHATIAN: jangan pernah memakai User::create($request->all()).
    // Selalu pilih field secara eksplisit agar 'role' tidak bisa diisi dari form.
    protected $fillable = ['username', 'email', 'password', 'profile_picture', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // ===== Relasi =====
    // songs() & albums() ditambahkan Syahid (Modul 2) -> jangan duplikat.
    // playlists() akan ditambahkan setelah model Playlist (Rafli) ter-merge:
    // public function playlists() { return $this->hasMany(Playlist::class); }

    // ===== Helper =====
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getProfilePictureUrlAttribute(): string
    {
        if ($this->profile_picture) {
            return asset('storage/'.$this->profile_picture);
        }

        $initial = urlencode(strtoupper(mb_substr($this->username ?? 'U', 0, 1)));
        return "https://placehold.co/300x300/c9a7f9/ffffff?text={$initial}";
    }

    // Saat user dihapus (hapus akun sendiri ATAU dihapus admin), bersihkan file fisiknya.
    // Baris di database ikut terhapus lewat cascade FK, tetapi FILE di storage tidak.
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $paths = [$user->profile_picture];

            foreach ($user->songs as $song) {
                $paths[] = $song->file_path;
                $paths[] = $song->cover_path;
            }
            foreach ($user->albums as $album) {
                $paths[] = $album->cover_path;
            }
            // TODO (koordinasi dgn Rafli): tambahkan cover playlist milik user di sini.

            Storage::disk('public')->delete(array_filter($paths));
        });
    }
}
```

Catatan:
- Event `deleting` hanya jalan kalau dihapus lewat `$user->delete()`, **bukan** `User::where(...)->delete()` (mass delete). Kabari Nazla agar `AdminUserController` memakai `$user->delete()`.
- Blok `booted()` bergantung pada `songs()` dan `albums()` milik Syahid. Kalau modul 2 belum ter-merge saat PR #1, komentari dulu dua `foreach` itu.

✅ **Di sini buat PR #1** (migration `users` + UserFactory + UserSeeder + model `User`). Kabari Syahid, Rafli, dan Nazla di PR bahwa kolom `username` dan `role` sudah tersedia.

---

## Fase 3 — Form Request

```bash
php artisan make:request RegisterRequest
php artisan make:request LoginRequest
php artisan make:request ProfileUpdateRequest
```

### `RegisterRequest.php`
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // regex melarang spasi & '@' agar username tidak tertukar dengan email saat login
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9_.-]+$/', 'unique:users,username'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'terms'    => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex'    => 'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan strip.',
            'username.unique'   => 'Username sudah dipakai.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted'    => 'Kamu harus menyetujui Terms of Service dan Privacy Policy.',
        ];
    }
}
```

### `LoginRequest.php`
Di UI field-nya "Email or Username", jadi namanya `login` (bukan `email` seperti di class diagram; perbarui diagram jika perlu).
```php
public function authorize(): bool { return true; }

public function rules(): array
{
    return [
        'login'    => ['required', 'string'],
        'password' => ['required', 'string'],
        'remember' => ['nullable', 'boolean'],
    ];
}

public function messages(): array
{
    return [
        'login.required'    => 'Email atau username wajib diisi.',
        'password.required' => 'Password wajib diisi.',
    ];
}
```

### `ProfileUpdateRequest.php`
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; } // route sudah dilindungi middleware 'auth'

    public function rules(): array
    {
        $id = $this->user()->id;

        return [
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9_.-]+$/',
                           Rule::unique('users', 'username')->ignore($id)],
            'email'    => ['required', 'email', 'max:100',
                           Rule::unique('users', 'email')->ignore($id)],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // 2 MB
        ];
    }

    public function messages(): array
    {
        return [
            'username.regex'          => 'Username hanya boleh berisi huruf, angka, titik, garis bawah, dan strip.',
            'profile_picture.image'   => 'File harus berupa gambar.',
            'profile_picture.mimes'   => 'Format foto harus JPG atau PNG.',
            'profile_picture.max'     => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
```

---

## Fase 4 — Controller

Sesuai class diagram, ada dua controller. Jangan memakai `breeze`/`jetstream`; semua ditulis manual.

```bash
php artisan make:controller UserController
php artisan make:controller ProfileController
```

### 4.1 `UserController.php` (register, login, logout)
```php
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
```

### 4.2 `ProfileController.php` (lihat, edit, update, hapus akun)
```php
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
```

Catatan: menghapus user akan ikut menghapus **lagu dan album miliknya** (FK `cascadeOnDelete` di migration Syahid), sesuai teks di UI ("All your personal data, playlists, and account settings will be permanently deleted"). Pastikan Rafli juga memakai `cascadeOnDelete` untuk `playlists.user_id`.

---

## Fase 5 — Route

### 5.1 `routes/web.php`
Tambahkan blok milikmu (jangan hapus route anggota lain; saat konflik, **simpan dua-duanya**):

```php
use App\Http\Controllers\{UserController, ProfileController};

// ===== MODUL USER & PROFILE (Nazril) =====
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'create'])->name('register');
    Route::post('/register', [UserController::class, 'store'])->name('register.store');

    Route::get('/login', [UserController::class, 'showLogin'])->name('login');   // nama 'login' WAJIB persis
    Route::post('/login', [UserController::class, 'login'])
        ->middleware('throttle:5,1')                                              // maks 5 percobaan / menit
        ->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
```

⚠️ **Hapus route sementara milik Syahid** kalau masih ada di `web.php` (ia menyuruh menghapusnya sebelum PR, tapi sering terlewat):
```php
Route::get('/login', fn () => 'login page')->name('login');            // HAPUS
Route::get('/dev-login', function () { ... });                         // HAPUS
```
Dua route bernama `login` akan saling menimpa dan halaman login asli tidak muncul.

### 5.2 Arah redirect untuk user yang sudah login
Di `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    // user yang sudah login lalu membuka /login atau /register -> kembali ke Home
    $middleware->redirectUsersTo(fn () => route('home'));
})
```
Route `home` dibuat Syahid. Kalau belum ter-merge, ganti sementara dengan `fn () => '/'`.

### 5.3 Cek
```bash
php artisan route:list --name=login
php artisan route:list --name=profile
```

---

## Fase 6 — Blade View

Struktur folder (sesuai dokumen):
```
resources/views/
├── layouts/guest.blade.php          (baru)
├── components/password-input.blade.php (baru)
├── auth/   login, register
├── profile/ show, edit
└── welcome.blade.php                (opsional, landing page)
```
Yang **tidak** dibuat ulang: `layouts/app.blade.php` (milik bersama, hanya diubah sedikit di 6.7).

### 6.1 `layouts/guest.blade.php` (layout kartu di tengah, untuk Login/Register)
```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OpenTune')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .btn-grad { background: linear-gradient(90deg, #c9a7f9, #5b36e8); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#ece7fb] via-[#f6f3fd] to-[#d6c9f1] text-gray-900">
<main class="flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
        <a href="{{ route('home') }}" class="mb-4 block text-center text-xl font-semibold text-[#5b36e8]">🎧 OpenTune</a>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs text-green-700">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700">
                <ul class="list-disc pl-4">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>
</body>
</html>
```

### 6.2 `components/password-input.blade.php` (input password + tombol Show/Hide)
```blade
@props(['name' => 'password', 'id' => null, 'placeholder' => '', 'autocomplete' => 'current-password'])
@php $id = $id ?? $name; @endphp

<div class="relative">
    <input type="password" name="{{ $name }}" id="{{ $id }}" placeholder="{{ $placeholder }}"
           autocomplete="{{ $autocomplete }}"
           {{ $attributes->merge(['class' => 'w-full rounded-lg border px-3 py-2 pr-14 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a7f9]']) }}>
    <button type="button" class="absolute inset-y-0 right-3 text-xs text-gray-500"
            onclick="const i = document.getElementById('{{ $id }}'); i.type = i.type === 'password' ? 'text' : 'password'; this.textContent = i.type === 'password' ? 'Show' : 'Hide'">Show</button>
</div>
```
Pemakaian: `<x-password-input name="password" required />`

### 6.3 `auth/login.blade.php`
```blade
@extends('layouts.guest')
@section('title', 'Login')

@section('content')
@php $input = 'w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a7f9]'; @endphp

<h1 class="text-center text-2xl font-semibold">Welcome back</h1>
<p class="mb-5 mt-1 text-center text-xs text-gray-500">Please enter your detail to sign in.</p>

<form action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="text-xs font-medium">Email or Username</label>
        <input name="login" value="{{ old('login') }}" placeholder="Enter your email or username"
               class="{{ $input }}" autocomplete="username" required autofocus>
    </div>

    <div>
        <label class="text-xs font-medium">Password</label>
        <x-password-input name="password" placeholder="Enter your password" required />
    </div>

    <label class="flex items-center gap-2 text-xs">
        <input type="checkbox" name="remember" value="1" class="accent-[#5b36e8]"> Remember Me
    </label>

    <button type="submit" class="btn-grad w-full rounded-lg py-2 text-sm font-medium text-white">Sign in</button>
</form>

<p class="mt-5 text-center text-xs text-gray-500">
    Don't have an account? <a href="{{ route('register') }}" class="font-medium text-[#5b36e8] hover:underline">Sign Up</a>
</p>
@endsection
```
Tidak dibuat: "Forgot Password?" dan "Continue with Google". Keduanya ada di desain UI tetapi **tidak ada di cakupan rancangan maupun ERD** (butuh tabel reset token + mailer, dan Socialite + kolom `google_id`). Kalau mau dikerjakan, diskusikan dulu dengan tim karena mengubah ERD.

### 6.4 `auth/register.blade.php`
```blade
@extends('layouts.guest')
@section('title', 'Sign Up')

@section('content')
@php $input = 'w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a7f9]'; @endphp

<h1 class="text-center text-2xl font-semibold">Create an Account</h1>
<p class="mb-5 mt-1 text-center text-xs text-gray-500">Join OpenTune to discover, create, and share your music journey.</p>

<form action="{{ route('register.store') }}" method="POST" class="space-y-3">
    @csrf
    <div>
        <label class="text-xs font-medium">Username</label>
        <input name="username" value="{{ old('username') }}" placeholder="slimut69" class="{{ $input }}"
               autocomplete="username" maxlength="50" required autofocus>
    </div>

    <div>
        <label class="text-xs font-medium">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com"
               class="{{ $input }}" autocomplete="email" maxlength="100" required>
    </div>

    <div>
        <label class="text-xs font-medium">Password</label>
        <x-password-input name="password" autocomplete="new-password" required />
        <p class="mt-1 text-[11px] text-gray-500">Minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</p>
    </div>

    <div>
        <label class="text-xs font-medium">Confirm Password</label>
        <x-password-input name="password_confirmation" id="password_confirmation" autocomplete="new-password" required />
    </div>

    <label class="flex items-start gap-2 text-xs">
        <input type="checkbox" name="terms" value="1" class="mt-0.5 accent-[#5b36e8]" @checked(old('terms')) required>
        <span>I agree to OpenTune's Terms of Service and Privacy Policy</span>
    </label>

    <button type="submit" class="btn-grad w-full rounded-lg py-2 text-sm font-medium text-white">Sign Up</button>
</form>

<p class="mt-5 text-center text-xs text-gray-500">
    Already have an account? <a href="{{ route('login') }}" class="font-medium text-[#5b36e8] hover:underline">Log In</a>
</p>
@endsection
```

### 6.5 `profile/show.blade.php` (halaman Profile read-only)
```blade
@extends('layouts.app')
@section('title', 'Profil')

@section('content')
@php $grid = 'grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6'; @endphp

{{-- Banner profil --}}
<div class="mb-6 flex flex-col items-center gap-6 rounded-2xl bg-gradient-to-r from-[#c9a7f9] to-[#ece7fb] p-6 sm:flex-row">
    <img src="{{ $user->profile_picture_url }}" alt="Foto profil"
         class="h-32 w-32 rounded-full border-4 border-white object-cover shadow">
    <div class="text-center sm:text-left">
        <h1 class="text-3xl font-semibold">{{ $user->username }}
            @if ($user->isAdmin()) <span class="ml-1 rounded bg-[#5b36e8] px-2 py-0.5 align-middle text-xs text-white">Admin</span> @endif
        </h1>
        <p class="text-sm text-gray-700">{{ $user->email }}</p>

        <div class="mt-3 flex justify-center gap-2 sm:justify-start">
            <a href="{{ route('profile.edit') }}" class="btn-grad rounded-lg px-4 py-1.5 text-sm text-white">Edit Profile</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="rounded-lg border bg-white px-4 py-1.5 text-sm">Logout</button>
            </form>
        </div>
    </div>
</div>

{{-- Top artist --}}
<h2 class="mb-3 font-semibold">Top Artist</h2>
<div class="mb-8 flex flex-wrap gap-6">
    @forelse ($topArtists as $artist)
        <a href="{{ route('artists.show', $artist) }}" class="w-24 text-center">
            <img src="{{ $artist->image_url }}" class="mx-auto h-20 w-20 rounded-full object-cover" alt="">
            <p class="mt-1 truncate text-sm font-semibold">{{ $artist->name }}</p>
            <p class="text-xs text-gray-500">{{ $artist->songs_count }} lagu</p>
        </a>
    @empty
        <p class="text-sm text-gray-500">Belum ada data. Upload lagu untuk melihat artist favoritmu di sini.</p>
    @endforelse
</div>

{{-- Lagu yang diupload --}}
<h2 class="mb-3 font-semibold">Lagu yang Kamu Upload</h2>
<div class="{{ $grid }} mb-8">
    @forelse ($songs as $song) <x-song-card :song="$song" />
    @empty <p class="col-span-full text-sm text-gray-500">Belum ada lagu.</p>
    @endforelse
</div>

{{-- Album --}}
<h2 class="mb-3 font-semibold">Album Kamu</h2>
<div class="{{ $grid }} mb-8">
    @forelse ($albums as $album) <x-album-card :album="$album" />
    @empty <p class="col-span-full text-sm text-gray-500">Belum ada album.</p>
    @endforelse
</div>

{{-- Playlist (data dari modul Rafli) --}}
<h2 class="mb-3 font-semibold">My Playlist</h2>
<div class="{{ $grid }}">
    @forelse ($playlists as $playlist)
        @if (Route::has('playlists.show'))
            <a href="{{ route('playlists.show', $playlist) }}" class="rounded-lg border bg-white p-3 text-sm font-semibold hover:bg-gray-50">{{ $playlist->name }}</a>
        @else
            <div class="rounded-lg border bg-white p-3 text-sm font-semibold">{{ $playlist->name }}</div>
        @endif
    @empty
        <p class="col-span-full text-sm text-gray-500">Belum ada playlist.</p>
    @endforelse
</div>
@endsection
```
Bagian "Recently Played" dari mockup tidak dibuat karena butuh tabel riwayat putar yang tidak ada di ERD. Nama route playlist (`playlists.show`) harus disesuaikan dengan milik Rafli.

### 6.6 `profile/edit.blade.php` (Edit Profile + Account Settings + modal hapus akun)
```blade
@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
@php $input = 'w-full rounded-lg border bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a7f9]'; @endphp

<div class="mx-auto max-w-3xl">
    <h1 class="mb-4 text-2xl font-semibold">Edit Profile &amp; Account Setting</h1>

    {{-- Edit Profile --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
          class="rounded-2xl bg-gradient-to-br from-[#d9c4fb] to-white p-6">
        @csrf @method('PUT')

        <div class="grid gap-6 md:grid-cols-[220px_1fr]">
            <div class="text-center">
                <p class="mb-2 font-medium">Profile Photo</p>
                <img id="photo-preview" src="{{ $user->profile_picture_url }}" alt=""
                     class="mx-auto h-40 w-40 rounded-full object-cover shadow">
                <label class="mt-3 inline-block cursor-pointer text-sm text-[#5b36e8] hover:underline">
                    Change Photo
                    <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png" class="hidden"
                           onchange="if (this.files[0]) document.getElementById('photo-preview').src = URL.createObjectURL(this.files[0])">
                </label>
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Max 2MB</p>
            </div>

            <div class="space-y-4">
                <h2 class="font-medium">Account Details</h2>
                <div>
                    <label class="text-xs font-medium">Username</label>
                    <input name="username" value="{{ old('username', $user->username) }}" class="{{ $input }}" maxlength="50" required>
                </div>
                <div>
                    <label class="text-xs font-medium">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="{{ $input }}" maxlength="100" required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-grad rounded-lg px-6 py-2 text-sm text-white">Save Changes</button>
                    <a href="{{ route('profile.show') }}" class="rounded-lg border bg-white px-6 py-2 text-sm">Cancel</a>
                </div>
            </div>
        </div>
    </form>

    {{-- Danger Zone --}}
    <h2 class="mb-2 mt-8 text-lg font-semibold">Danger Zone</h2>
    <div class="rounded-2xl border border-red-200 bg-red-50 p-6">
        <h3 class="font-semibold text-red-700">Permanently Delete Account</h3>
        <p class="mt-1 text-sm text-gray-700">
            Tindakan ini permanen dan tidak bisa dibatalkan. Seluruh data pribadi, lagu, album, dan playlist milikmu akan dihapus.
        </p>
        <button type="button" onclick="document.getElementById('delete-modal').showModal()"
                class="mt-3 rounded-lg bg-red-600 px-5 py-1.5 text-sm text-white">Delete Account</button>
    </div>
</div>

{{-- Modal konfirmasi hapus akun --}}
<dialog id="delete-modal" class="w-full max-w-sm rounded-xl p-6 shadow-xl backdrop:bg-black/40">
    <h3 class="text-lg font-semibold text-red-700">Confirm Account Deletion</h3>
    <p class="mb-3 text-sm text-gray-600">Enter your password to confirm account deletion</p>

    <form action="{{ route('profile.destroy') }}" method="POST" class="space-y-3">
        @csrf @method('DELETE')
        <x-password-input name="password" id="delete-password" required />

        @if ($errors->userDeletion->isNotEmpty())
            <p class="text-xs text-red-600">{{ $errors->userDeletion->first('password') }}</p>
        @endif

        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-red-600 px-5 py-1.5 text-sm text-white">Confirm Delete</button>
            <button type="button" onclick="this.closest('dialog').close()" class="rounded-lg bg-gray-200 px-5 py-1.5 text-sm">Cancel</button>
        </div>
    </form>
</dialog>

{{-- Kalau password salah, buka lagi modalnya --}}
@if ($errors->userDeletion->isNotEmpty())
    <script>document.getElementById('delete-modal').showModal()</script>
@endif
@endsection
```

### 6.7 Ubah sedikit `layouts/app.blade.php` (satu-satunya bagian layout yang disentuh)
Di layout Syahid, avatar masih berupa link statis ke `/profile` dan belum ada tombol Logout. Cari blok ini:
```blade
{{-- link profil dikerjakan Nazril; sesuaikan --}}
<a href="{{ url('/profile') }}">
    <img src="{{ auth()->user()->profile_picture ? asset('storage/'.auth()->user()->profile_picture) : 'https://placehold.co/80x80/c9a7f9/ffffff?text=U' }}"
         class="h-9 w-9 rounded-full object-cover" alt="Profil">
</a>
```
Ganti dengan dropdown:
```blade
<details class="relative">
    <summary class="cursor-pointer list-none">
        <img src="{{ auth()->user()->profile_picture_url }}" class="h-9 w-9 rounded-full object-cover" alt="Profil">
    </summary>
    <div class="absolute right-0 z-20 mt-2 w-48 rounded-lg border bg-white text-sm shadow">
        <p class="truncate border-b px-4 py-2 text-xs text-gray-500">{{ auth()->user()->username }}</p>
        <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-50">Profil</a>
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-50">Edit Profil</a>
        @if (auth()->user()->isAdmin() && Route::has('admin.dashboard'))
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-50">Dashboard Admin</a>
        @endif
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="w-full px-4 py-2 text-left text-red-600 hover:bg-gray-50">Logout</button>
        </form>
    </div>
</details>
```
Karena `layouts/app.blade.php` dipakai semua orang, **kabari grup** dan minta Syahid/Nazla tidak mengubah bagian yang sama di waktu bersamaan (sumber konflik merge paling umum).

### 6.8 (Opsional) Landing page — `welcome.blade.php`
Activity diagram menyebut Landing Page dengan tombol Login dan Sign Up. Karena `/` sudah dipakai Home milik Syahid (publik, dengan tombol Login untuk guest), buat landing di URL terpisah dulu:

```php
// routes/web.php (di dalam blok guest)
Route::view('/welcome', 'welcome')->name('welcome');
```
```blade
@extends('layouts.guest')
@section('title', 'Welcome')

@section('content')
<h1 class="text-center text-3xl font-semibold leading-tight">Discover, create, &amp; share your music</h1>
<p class="my-4 text-center text-sm text-gray-600">
    Access thousands of songs, albums, and playlist anytime. Upload your own tracks and join the rhythm.
</p>
<div class="flex gap-2">
    <a href="{{ route('register') }}" class="btn-grad flex-1 rounded-lg py-2 text-center text-sm text-white">Get Started</a>
    <a href="{{ route('login') }}" class="flex-1 rounded-lg border py-2 text-center text-sm">Login</a>
</div>
@endsection
```
Kalau tim ingin guest otomatis diarahkan ke landing saat membuka `/`, diskusikan dengan Syahid karena itu mengubah route `home`-nya.

---

## Fase 7 — Testing

### 7.1 Checklist manual
Jalankan `php artisan serve`, lalu uji berurutan:

| # | Skenario | Hasil yang diharapkan |
|---|---|---|
| 1 | Register data valid | Redirect ke `/login` + pesan sukses; user baru berrole `user` di tabel `users` |
| 2 | Register email yang sudah terdaftar | Pesan "Email sudah terdaftar" |
| 3 | Register username sudah dipakai / mengandung spasi atau `@` | Ditolak dengan pesan validasi |
| 4 | Register password < 8 karakter / tanpa angka / tanpa huruf besar | Ditolak |
| 5 | Register tanpa mencentang Terms | Ditolak |
| 6 | Register sambil menyisipkan `role=admin` lewat DevTools | User tetap berrole `user` |
| 7 | Login memakai email | Masuk ke Home, avatar tampil di topbar |
| 8 | Login memakai username | Masuk ke Home |
| 9 | Login password salah | "Email/username atau kata sandi salah", input login tetap terisi |
| 10 | Login salah 6x dalam 1 menit | Dibatasi (HTTP 429 Too Many Requests) |
| 11 | Login sebagai admin | Ke Home (atau ke dashboard bila route `admin.dashboard` sudah ada) |
| 12 | Buka `/login` saat sudah login | Redirect ke Home |
| 13 | Buka `/profile` tanpa login | Redirect ke `/login`, setelah login kembali ke `/profile` |
| 14 | Klik avatar → Profil | Tampil username, email, lagu/album milik sendiri |
| 15 | Edit username & email | Tersimpan, redirect ke profil dengan pesan sukses |
| 16 | Edit ke username/email milik user lain | Ditolak |
| 17 | Upload foto JPG/PNG ≤ 2 MB | Foto baru tampil di profil & topbar; **file foto lama terhapus** di `storage/app/public/profiles` |
| 18 | Upload foto GIF atau > 2 MB | Pesan error validasi |
| 19 | Delete Account dengan password salah | Modal terbuka lagi dengan pesan error; akun tetap ada |
| 20 | Delete Account dengan password benar | Logout, redirect ke Home, user + lagu/album/foto miliknya hilang (cek DB dan folder storage) |
| 21 | Hapus akun satu-satunya admin | Ditolak |
| 22 | Logout | Session berakhir, tombol Login muncul lagi di Home |

### 7.2 Feature test — `php artisan make:test AuthTest`
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function registerData(array $override = []): array
    {
        return array_merge([
            'username'              => 'slimut69',
            'email'                 => 'slimut@test.com',
            'password'              => 'Password1',
            'password_confirmation' => 'Password1',
            'terms'                 => '1',
        ], $override);
    }

    public function test_user_can_register(): void
    {
        $this->post(route('register.store'), $this->registerData())
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', ['username' => 'slimut69', 'role' => 'user']);
    }

    public function test_register_cannot_set_role(): void
    {
        $this->post(route('register.store'), $this->registerData(['role' => 'admin']));

        $this->assertDatabaseHas('users', ['username' => 'slimut69', 'role' => 'user']);
    }

    public function test_register_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'slimut@test.com']);

        $this->post(route('register.store'), $this->registerData())
            ->assertSessionHasErrors('email');
    }

    public function test_register_rejects_weak_password(): void
    {
        $this->post(route('register.store'), $this->registerData([
            'password' => 'abcdefgh', 'password_confirmation' => 'abcdefgh',
        ]))->assertSessionHasErrors('password');
    }

    public function test_user_can_login_with_email_or_username(): void
    {
        $user = User::factory()->create(['password' => 'Password1']);

        $this->post(route('login.attempt'), ['login' => $user->email, 'password' => 'Password1'])
            ->assertRedirect();
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'));
        $this->assertGuest();

        $this->post(route('login.attempt'), ['login' => $user->username, 'password' => 'Password1']);
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => 'Password1']);

        $this->post(route('login.attempt'), ['login' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_profile(): void
    {
        $this->get(route('profile.show'))->assertRedirect(route('login'));
    }
}
```

### 7.3 Feature test — `php artisan make:test ProfileTest`
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_username_and_email(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('profile.update'), [
            'username' => 'namabaru',
            'email'    => 'baru@test.com',
        ])->assertRedirect(route('profile.show'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'namabaru', 'email' => 'baru@test.com']);
    }

    public function test_update_rejects_username_used_by_other_user(): void
    {
        User::factory()->create(['username' => 'dipakai']);
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('profile.update'), [
            'username' => 'dipakai', 'email' => $user->email,
        ])->assertSessionHasErrors('username');
    }

    public function test_user_can_upload_and_replace_profile_picture(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('profile.update'), [
            'username' => $user->username, 'email' => $user->email,
            'profile_picture' => UploadedFile::fake()->image('a.jpg'),
        ]);
        $old = $user->fresh()->profile_picture;
        Storage::disk('public')->assertExists($old);

        $this->actingAs($user)->put(route('profile.update'), [
            'username' => $user->username, 'email' => $user->email,
            'profile_picture' => UploadedFile::fake()->image('b.png'),
        ]);
        Storage::disk('public')->assertMissing($old);          // file lama terhapus
        Storage::disk('public')->assertExists($user->fresh()->profile_picture);
    }

    public function test_profile_picture_over_2mb_is_rejected(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('profile.update'), [
            'username' => $user->username, 'email' => $user->email,
            'profile_picture' => UploadedFile::fake()->image('big.jpg')->size(3000),
        ])->assertSessionHasErrors('profile_picture');
    }

    public function test_delete_account_requires_correct_password(): void
    {
        $user = User::factory()->create(['password' => 'Password1']);

        $this->actingAs($user)->delete(route('profile.destroy'), ['password' => 'salah'])
            ->assertSessionHasErrorsIn('userDeletion', 'password');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_user_can_delete_account_and_photo_is_removed(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profiles/foto.jpg', 'x');
        $user = User::factory()->create(['password' => 'Password1', 'profile_picture' => 'profiles/foto.jpg']);

        $this->actingAs($user)->delete(route('profile.destroy'), ['password' => 'Password1']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        Storage::disk('public')->assertMissing('profiles/foto.jpg');
        $this->assertGuest();
    }

    public function test_last_admin_cannot_delete_account(): void
    {
        $admin = User::factory()->admin()->create(['password' => 'Password1']);

        $this->actingAs($admin)->delete(route('profile.destroy'), ['password' => 'Password1'])
            ->assertSessionHasErrorsIn('userDeletion', 'password');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
```
```bash
php artisan test
```
Catatan: `UploadedFile::fake()->image()` butuh ekstensi PHP **GD** aktif. `test_user_can_login_with_email_or_username` memakai route `home` (Syahid) untuk redirect; kalau belum ter-merge, bagian itu tetap lolos karena hanya `assertRedirect()` tanpa tujuan. Test `show` profile tidak ditulis karena bergantung pada layout dan komponen kartu dari modul 2.

---

## Fase 8 — Git, PR, dan Integrasi

### 8.1 Commit per langkah
```bash
git add database/ app/Models
git commit -m "feat(user): update users migration to ERD, add factory, seeder and User model"
# -> push & buka PR #1 sekarang (lihat 8.2)

git add app/Http/Requests
git commit -m "feat(user): add register, login and profile update form requests"

git add app/Http/Controllers routes bootstrap
git commit -m "feat(user): add UserController, ProfileController, routes and guest redirect"

git add resources/views
git commit -m "feat(user): add login, register, profile and edit profile views"

git add tests
git commit -m "test(user): add auth and profile feature tests"

git push -u origin feature/user
```
Buka Pull Request `feature/user` → `main` di GitHub. PR #1 (migration + model) sebaiknya dibuka **lebih dulu**, secepat Fase 2 selesai.

### 8.2 Urutan merge yang disarankan
1. **PR #1 Nazril** (tabel `users`, factory, seeder, model) → paling awal, karena modul lain bergantung padanya.
2. PR #1 Syahid (migration musik + model), lalu PR Rafli (playlist).
3. PR lengkap Nazril (auth + profile) dan PR lengkap Syahid, lalu PR Nazla.

### 8.3 Titik rawan konflik & kesepakatan tim
| Titik | Apa yang perlu disepakati |
|---|---|
| Migration `users` | Satu-satunya yang mengubah file `0001_01_01_000000_create_users_table.php` adalah Nazril. Setelah merge, **semua** orang jalankan `php artisan migrate:fresh --seed` |
| Kolom `username` UNIQUE & `remember_token` | Beda dari ERD; kabari tim dan perbarui dokumen ERD |
| Route `login` sementara milik Syahid | Harus dihapus; route `login` asli ada di blok milik Nazril |
| `routes/web.php` | Tiap orang punya blok sendiri dengan komentar pemisah; saat konflik **simpan dua-duanya** |
| `layouts/app.blade.php` | Hanya blok avatar yang diubah Nazril (6.7); sepakati agar tidak diedit bersamaan |
| `DatabaseSeeder` | Gabungkan `UserSeeder` + `GenreSeeder` (+ seeder lain), jangan saling menimpa |
| `users.role` | Nilai hanya `'user'` dan `'admin'`; dipakai Policy Syahid dan middleware Nazla |
| Admin Login (Nazla) | Login Nazril sudah melayani user dan admin; admin diarahkan ke `admin.dashboard` bila route-nya ada. Kalau Nazla ingin halaman login admin terpisah, diskusikan dulu |
| Middleware admin | Milik Nazla. Setelah ada, Syahid mengganti `adminOnly()` di `ArtistController` dengan middleware tersebut |
| Hapus user oleh admin | `AdminUserController` harus memakai `$user->delete()` (bukan mass delete) agar file ikut dibersihkan |
| `User::playlists()` | Ditambahkan setelah model `Playlist` milik Rafli ter-merge |
| `playlists.user_id` & cover playlist | Rafli memakai `cascadeOnDelete`; cover playlist ditambahkan ke daftar `$paths` di `User::booted()` |
| Nama route playlist | Samakan dengan Rafli (`playlists.show`) untuk link di halaman Profile |

### 8.4 Setelah merge
```bash
git checkout main && git pull origin main
php artisan migrate:fresh --seed
php artisan test
```
Cek ulang integrasi: login → upload lagu (Syahid) → lagu tampil di profil; buat playlist (Rafli) → muncul di "My Playlist"; login admin → akses dashboard (Nazla); hapus akun user yang punya lagu → file lagunya hilang dari `storage/app/public`.

### 8.5 Opsional (jika waktu cukup)
- **Forgot Password:** butuh tabel `password_reset_tokens` (sudah ada dari migration bawaan) dan konfigurasi mail. Diskusikan dengan tim karena di luar cakupan rancangan.
- **Continue with Google:** butuh Laravel Socialite + kolom `google_id` (mengubah ERD).
- **Recently Played / Top Artist bulanan:** butuh tabel log pemutaran (satu fitur yang sama dengan "Most Listened This Month" di modul 2).
- **Verifikasi email:** tidak ada di rancangan.

---

## Ringkasan "Selesai" (Definition of Done)
- [ ] Migration `users` sesuai ERD (username, email, password, profile_picture, role) + Factory + Seeder admin/user
- [ ] Model `User` dengan cast hashed, helper `isAdmin()`, accessor foto, dan pembersihan file saat dihapus
- [ ] Register, Login (email atau username, Remember Me, throttle), dan Logout berjalan
- [ ] Halaman Profile menampilkan data akun, top artist, lagu, album, dan playlist milik sendiri
- [ ] Edit Profile (username, email, foto ≤ 2 MB) dengan validasi unik dan penghapusan foto lama
- [ ] Hapus akun dengan konfirmasi password, aman dari penghapusan admin terakhir, dan file ikut terhapus
- [ ] `role` tidak bisa diisi dari form publik
- [ ] Checklist testing dan 2 feature test (Auth + Profile) lolos
- [ ] PR #1 sudah di-merge lebih awal, PR lengkap di-merge, dan integrasi dengan modul lain dicek
