<?php

namespace Tests\Feature;

use App\Models\ConsultationQuestion;
use App\Models\User;
use Database\Seeders\ConsultationQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ConsultationQuestionSeeder::class);
    }

    public function test_consultation_page_renders_questions()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/konsultasi');

        $response->assertStatus(200);
        $response->assertSee('Bagaimana kondisi wajah Anda sekitar 2-3 jam setelah mencuci muka');
        $response->assertSee('Berminyak di seluruh wajah (T-zone dan pipi)');
    }

    public function test_consultation_page_contains_eight_questions()
    {
        $this->assertSame(8, ConsultationQuestion::active()->count());
    }

    public function test_consultation_store_saves_consultation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/konsultasi', [
            'a1_sebum_condition' => 'oily',
            'a2_pore_size' => 'large',
            'a3_reaction_history' => ['occasional_breakout'],
            'concerns' => ['acne'],
            'c1_reactivity' => 'resistant',
            'c2_experience_level' => 'beginner',
            'c3_retinol_tolerance' => 'unknown',
            'c4_special_conditions' => ['none'],
        ]);

        $response->assertRedirect(route('user.consultation.result'));
        $this->assertDatabaseHas('consultations', [
            'user_id' => $user->id,
            'skin_type' => 'oily',
        ]);
    }

    public function test_consultation_result_page_renders_inferred_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/konsultasi', [
            'a1_sebum_condition' => 'oily',
            'a2_pore_size' => 'large',
            'a3_reaction_history' => ['occasional_breakout'],
            'concerns' => ['acne'],
            'c1_reactivity' => 'resistant',
            'c2_experience_level' => 'beginner',
            'c3_retinol_tolerance' => 'unknown',
            'c4_special_conditions' => ['none'],
        ]);

        $response = $this->actingAs($user)->get('/konsultasi/hasil');

        $response->assertStatus(200);
        $response->assertSee('Hasil Analisis Profil');
        $response->assertSee('Kulit Berminyak');
        $response->assertSee('Jerawat');
        $response->assertSee('Peringatan Sistem');
    }

    public function test_consultation_result_redirects_when_no_consultation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/konsultasi/hasil');

        $response->assertRedirect(route('user.consultation'));
    }
}
