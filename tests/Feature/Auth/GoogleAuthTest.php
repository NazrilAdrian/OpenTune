<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.google.client_id' => 'test-client-id',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    public function test_google_redirect_uses_state_and_pkce(): void
    {
        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect();

        $query = [];
        parse_str((string) parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);

        $this->assertSame('S256', $query['code_challenge_method'] ?? null);
        $this->assertNotEmpty($query['state'] ?? null);
        $response->assertSessionHas('google_oauth_code_verifier');
    }

    public function test_verified_google_email_creates_a_regular_user_and_logs_them_in(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'oauth2.googleapis.com/token' => Http::response(['access_token' => 'google-access-token']),
            'openidconnect.googleapis.com/v1/userinfo' => Http::response([
                'sub' => 'google-user-id',
                'email' => 'listener@example.com',
                'email_verified' => true,
                'name' => 'OpenTune Listener',
            ]),
        ]);

        $response = $this->withSession([
            'google_oauth_state' => 'valid-state',
            'google_oauth_code_verifier' => str_repeat('v', 64),
        ])->get(route('auth.google.callback', [
            'state' => 'valid-state',
            'code' => 'authorization-code',
        ]));

        $user = User::where('email', 'listener@example.com')->firstOrFail();

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame('user', $user->role);
        $this->assertSame('opentune-listener', $user->username);

        Http::assertSent(fn (HttpRequest $request): bool => $request->url() === 'https://oauth2.googleapis.com/token'
            && $request['code_verifier'] === str_repeat('v', 64)
        );
    }

    public function test_unverified_google_email_cannot_create_or_authenticate_a_user(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'oauth2.googleapis.com/token' => Http::response(['access_token' => 'google-access-token']),
            'openidconnect.googleapis.com/v1/userinfo' => Http::response([
                'sub' => 'google-user-id',
                'email' => 'listener@example.com',
                'email_verified' => false,
                'name' => 'OpenTune Listener',
            ]),
        ]);

        $response = $this->withSession([
            'google_oauth_state' => 'valid-state',
            'google_oauth_code_verifier' => str_repeat('v', 64),
        ])->get(route('auth.google.callback', [
            'state' => 'valid-state',
            'code' => 'authorization-code',
        ]));

        $response->assertRedirect(route('login'))->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_google_callback_rejects_a_mismatched_state_before_network_requests(): void
    {
        Http::preventStrayRequests();

        $response = $this->withSession([
            'google_oauth_state' => 'expected-state',
            'google_oauth_code_verifier' => str_repeat('v', 64),
        ])->get(route('auth.google.callback', [
            'state' => 'attacker-state',
            'code' => 'authorization-code',
        ]));

        $response->assertStatus(419);
    }
}
