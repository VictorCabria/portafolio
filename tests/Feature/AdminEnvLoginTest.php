<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEnvLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.admin.email' => 'admin@example.com', 'auth.admin.password' => 'secreto-123']);
    }

    public function test_env_credentials_create_admin_and_log_in(): void
    {
        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'secreto-123'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
    }

    public function test_env_credentials_reset_existing_admin_password(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'password' => 'vieja']);

        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'secreto-123'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
    }

    public function test_wrong_password_does_not_create_user(): void
    {
        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'otra'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'admin@example.com']);
    }

    public function test_without_env_credentials_nothing_changes(): void
    {
        config(['auth.admin.email' => null, 'auth.admin.password' => null]);

        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'secreto-123'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
