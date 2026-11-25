<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_email_does_not_disclose_unknown_accounts(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $registeredResponse = $this->post(route('password.email'), ['email' => $user->email]);
        $unknownResponse = $this->post(route('password.email'), ['email' => 'missing@example.com']);

        $registeredResponse->assertSessionHas('status');
        $unknownResponse->assertSessionHas('status', $registeredResponse->getSession()->get('status'));
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_valid_reset_token_changes_the_password(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'a-new-strong-password',
            'password_confirmation' => 'a-new-strong-password',
        ]);

        $response->assertRedirect(route('login'))->assertSessionHas('success');
        $this->assertTrue(Hash::check('a-new-strong-password', $user->fresh()->password));
    }

    public function test_invalid_reset_token_does_not_change_the_password(): void
    {
        $user = User::factory()->create();
        $originalPassword = $user->password;

        $response = $this->from(route('password.reset', ['token' => 'invalid-token']))
            ->post(route('password.update'), [
                'token' => 'invalid-token',
                'email' => $user->email,
                'password' => 'a-new-strong-password',
                'password_confirmation' => 'a-new-strong-password',
            ]);

        $response->assertRedirect(route('password.reset', ['token' => 'invalid-token']))
            ->assertSessionHasErrors('email');
        $this->assertSame($originalPassword, $user->fresh()->password);
    }

    public function test_password_reset_forms_are_reachable(): void
    {
        $this->get(route('password.request'))->assertOk();
        $this->get(route('password.reset', ['token' => 'sample-token']))->assertOk();
    }
}
