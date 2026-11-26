<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('login')->with(
                'google_error',
                'Login Google belum diaktifkan di lingkungan ini. Admin perlu mengatur GOOGLE_CLIENT_ID dan '
                    .'GOOGLE_CLIENT_SECRET di .env, serta mendaftarkan callback URL di Google Cloud. '
                    .'Jangan bagikan atau commit secret; Anda tetap bisa login dengan username atau email.'
            );
        }

        $state = Str::random(40);
        $codeVerifier = Str::random(64);
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');

        session([
            'google_oauth_state' => $state,
            'google_oauth_code_verifier' => $codeVerifier,
        ]);

        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ], '', '&', PHP_QUERY_RFC3986);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?'.$query);
    }

    /**
     * @throws ConnectionException
     */
    public function callback(Request $request): RedirectResponse
    {
        $expectedState = $request->session()->pull('google_oauth_state');
        $codeVerifier = $request->session()->pull('google_oauth_code_verifier');
        $returnedState = $request->query('state');

        abort_unless(
            is_string($expectedState)
                && is_string($returnedState)
                && hash_equals($expectedState, $returnedState)
                && is_string($codeVerifier),
            419,
            'Sesi login Google tidak valid. Silakan coba lagi.'
        );

        if ($request->query('error') !== null) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login Google dibatalkan atau gagal. Silakan coba lagi.',
            ]);
        }

        $code = $request->query('code');

        abort_unless(is_string($code) && $code !== '', 400, 'Kode login Google tidak ditemukan.');

        $tokenResponse = Http::asForm()
            ->connectTimeout(3)
            ->timeout(10)
            ->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => config('services.google.redirect'),
                'grant_type' => 'authorization_code',
                'code_verifier' => $codeVerifier,
            ]);

        abort_unless($tokenResponse->successful(), 502, 'Google gagal memverifikasi login.');

        $accessToken = $tokenResponse->json('access_token');

        abort_unless(is_string($accessToken) && $accessToken !== '', 502, 'Token login Google tidak valid.');

        $profileResponse = Http::acceptJson()
            ->withToken($accessToken)
            ->connectTimeout(3)
            ->timeout(10)
            ->get('https://openidconnect.googleapis.com/v1/userinfo');

        abort_unless($profileResponse->successful(), 502, 'Profil Google tidak dapat diambil.');

        $profile = $profileResponse->json();
        $subject = is_array($profile) ? ($profile['sub'] ?? null) : null;
        $email = is_array($profile) ? ($profile['email'] ?? null) : null;

        if (
            ! is_string($subject)
            || $subject === ''
            || ! is_string($email)
            || filter_var($email, FILTER_VALIDATE_EMAIL) === false
            || ($profile['email_verified'] ?? false) !== true
        ) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Google harus memiliki email terverifikasi.',
            ]);
        }

        $email = strtolower($email);
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user) {
            $name = is_string($profile['name'] ?? null) ? $profile['name'] : '';

            $user = User::create([
                'username' => $this->uniqueUsername($name, $email),
                'email' => $email,
                'password' => Str::random(64),
                'role' => 'user',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }

    private function uniqueUsername(string $name, string $email): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = Str::before($email, '@');
        }

        $base = substr($base, 0, 40);
        $username = $base !== '' ? $base : 'listener';

        while (User::where('username', $username)->exists()) {
            $username = substr($base !== '' ? $base : 'listener', 0, 41)
                .'-'.Str::lower(Str::random(8));
        }

        return $username;
    }
}
