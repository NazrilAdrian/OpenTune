<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_username(): void
    {
        $user = User::factory()->create([
            'username' => 'open-tuner',
            'email' => 'tuner@example.com',
        ]);

        $response = $this->post(route('login.attempt'), [
            'login' => 'open-tuner',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_with_email(): void
    {
        $user = User::factory()->create([
            'username' => 'open-tuner',
            'email' => 'tuner@example.com',
        ]);

        $response = $this->post(route('login.attempt'), [
            'login' => 'tuner@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_an_identity_longer_than_100_characters(): void
    {
        $response = $this->from(route('login'))->post(route('login.attempt'), [
            'login' => str_repeat('a', 101),
            'password' => 'any-password',
        ]);

        $response->assertRedirect(route('login'))->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_login_rejects_an_empty_password(): void
    {
        $response = $this->from(route('login'))->post(route('login.attempt'), [
            'login' => 'tuner',
            'password' => '',
        ]);

        $response->assertRedirect(route('login'))->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_failed_login_shows_a_generic_identity_error(): void
    {
        $response = $this->from(route('login'))->post(route('login.attempt'), [
            'login' => 'unknown-user',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors('login')
            ->assertSessionMissing('_old_input.login');
        $this->assertGuest();
    }

    public function test_login_page_does_not_restore_credentials_or_request_browser_autofill(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['_old_input' => [
                'login' => 'previous-user',
                'password' => 'previous-password',
            ]])
            ->post(route('logout'))
            ->assertRedirect(route('landing'));

        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertSee('autocomplete="off"', false)
            ->assertDontSee('previous-user', false)
            ->assertDontSee('previous-password', false)
            ->assertDontSee('value="previous-user"', false)
            ->assertDontSee('value="previous-password"', false);
    }
}
