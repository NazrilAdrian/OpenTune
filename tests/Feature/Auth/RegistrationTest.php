<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_requires_terms_acceptance(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'username' => 'new-listener',
            'email' => 'listener@example.com',
            'password' => 'a-strong-password',
            'password_confirmation' => 'a-strong-password',
        ]);

        $response->assertRedirect(route('register'))->assertSessionHasErrors('terms');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_registration_creates_a_user_after_terms_are_accepted(): void
    {
        $response = $this->post(route('register.store'), [
            'username' => 'new-listener',
            'email' => 'listener@example.com',
            'password' => 'a-strong-password',
            'password_confirmation' => 'a-strong-password',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('login'))->assertSessionHas('success');

        $user = User::where('email', 'listener@example.com')->firstOrFail();

        $this->assertSame('user', $user->role);
        $this->assertTrue(Hash::check('a-strong-password', $user->password));
    }

    public function test_registration_rejects_an_email_longer_than_the_database_column(): void
    {
        $email = str_repeat('a', 90).'@example.com';

        $response = $this->from(route('register'))->post(route('register.store'), [
            'username' => 'new-listener',
            'email' => $email,
            'password' => 'a-strong-password',
            'password_confirmation' => 'a-strong-password',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('register'))->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_signup_password_indicators_start_hidden(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk()
            ->assertSee('data-check-password class="hidden', false)
            ->assertSee('data-check-confirm class="hidden', false);
    }
}
