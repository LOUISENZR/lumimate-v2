<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
        $response->assertSee('LumiMate');
    }

    public function test_register_page_renders()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Mulai ritual kulit Anda hari ini.');
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Rania Putri',
            'email' => 'rania@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'rania@example.com',
            'name' => 'Rania Putri',
            'role' => 'user',
        ]);
        $this->assertAuthenticated();
    }

    public function test_register_requires_terms()
    {
        $response = $this->post('/register', [
            'name' => 'Rania Putri',
            'email' => 'rania@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('terms');
    }

    public function test_user_can_login_and_logout()
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);

        $logout = $this->post('/logout');
        $logout->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_credentials()
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_redirected_away_from_login()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_guest_cannot_access_dashboard_and_menus()
    {
        foreach (['/dashboard', '/konsultasi', '/produk-saya', '/pemeriksa-konflik'] as $path) {
            $response = $this->get($path);
            $response->assertRedirect(route('login'));
        }
    }

    public function test_landing_page_public_and_accessible()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ritual kulit Anda');
        $response->assertSee('Mulai Gratis');
    }

    public function test_logged_in_user_sees_dashboard_link_on_landing()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ritual Saya');
    }
}