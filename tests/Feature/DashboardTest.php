<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_dashboard_renders_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('LumiMate');
        $response->assertSee('Kondisi Kulit');
        $response->assertSee('Dashboard');
        $response->assertSee('Kemajuan Ritual');
        $response->assertSee('RUNTUN HARI');
        $response->assertSee('Ritual Mingguan');
        $response->assertSee('Konsistensi Hidrasi');
        $response->assertSee('Peringatan Kandungan');
    }
}
