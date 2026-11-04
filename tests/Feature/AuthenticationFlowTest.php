<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_normalizes_identity_and_creates_a_donor_account(): void
    {
        $this->post(route('register.process'), [
            'username' => '  Donor_New  ',
            'email' => ' DONOR_NEW@EXAMPLE.TEST ',
            'password' => 'a-strong-password',
            'password_confirmation' => 'a-strong-password',
        ])->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $user = User::query()->where('username', 'donor_new')->firstOrFail();

        $this->assertSame('donor_new@example.test', $user->email);
        $this->assertSame('pendonor', $user->role);
        $this->assertTrue(Hash::check('a-strong-password', $user->password));
        $this->assertDatabaseCount('users', 1);
    }

    public function test_registration_rejects_duplicate_identity_and_unconfirmed_password(): void
    {
        User::create([
            'username' => 'registered-donor',
            'email' => 'registered@example.test',
            'password' => Hash::make('a-strong-password'),
            'role' => 'pendonor',
        ]);

        $this->from(route('register'))
            ->post(route('register.process'), [
                'username' => 'REGISTERED-DONOR',
                'email' => 'REGISTERED@example.test',
                'password' => 'a-strong-password',
                'password_confirmation' => 'different-password',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['username', 'email', 'password']);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_admin_login_redirects_to_the_admin_dashboard(): void
    {
        $admin = $this->createUser('site-admin', 'admin');

        $this->post(route('login.process'), [
            'username' => $admin->username,
            'password' => 'correct-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_donor_without_a_profile_is_sent_to_profile_setup_after_login(): void
    {
        $donor = $this->createUser('new-donor', 'pendonor');

        $this->post(route('login.process'), [
            'username' => $donor->username,
            'password' => 'correct-password',
        ])->assertRedirect(route('pendonor.profile.create'));

        $this->assertAuthenticatedAs($donor);
    }

    public function test_login_accepts_username_case_variation(): void
    {
        $donor = $this->createUser('case-sensitive-donor', 'pendonor');

        $this->post(route('login.process'), [
            'username' => 'CASE-SENSITIVE-DONOR',
            'password' => 'correct-password',
        ])->assertRedirect(route('pendonor.profile.create'));

        $this->assertAuthenticatedAs($donor);
    }

    public function test_invalid_login_shows_a_generic_error_and_keeps_the_guest_logged_out(): void
    {
        $donor = $this->createUser('known-donor', 'pendonor');

        $this->from(route('login'))
            ->post(route('login.process'), [
                'username' => $donor->username,
                'password' => 'incorrect-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_password_reset_request_sends_a_reset_notification(): void
    {
        Notification::fake();
        $user = $this->createUser('reset-donor', 'pendonor');

        $this->post(route('password.email'), [
            'email' => $user->email,
        ])->assertRedirect()
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_password_reset_replaces_the_password_and_consumes_the_token(): void
    {
        $user = $this->createUser('reset-complete', 'pendonor');
        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-strong-password',
            'password_confirmation' => 'new-strong-password',
        ])->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('new-strong-password', $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    private function createUser(string $username, string $role): User
    {
        return User::create([
            'username' => $username,
            'email' => $username.'@example.test',
            'password' => Hash::make('correct-password'),
            'role' => $role,
        ]);
    }
}
