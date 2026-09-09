<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_submission_creates_lead_and_redirects_with_success(): void
    {
        $response = $this->post(route('leads.store'), [
            'nombre' => 'Juan Perez',
            'email' => 'juan@example.com',
            'telefono' => '3141234567',
            'mensaje' => 'Me interesa esta propiedad',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'nombre' => 'Juan Perez',
            'email' => 'juan@example.com',
            'origen' => 'formulario',
            'estatus' => 'nuevo',
        ]);
    }

    public function test_honeypot_field_silently_rejects_without_creating_lead(): void
    {
        $response = $this->post(route('leads.store'), [
            'nombre' => 'Bot',
            'website' => 'http://spam.example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_time_trap_rejects_too_fast_submission_with_validation_error(): void
    {
        $response = $this->post(route('leads.store'), [
            'nombre' => 'Juan Perez',
            'form_time' => time(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_spam_text_pattern_silently_rejects_without_creating_lead(): void
    {
        $response = $this->post(route('leads.store'), [
            'nombre' => 'Juan Perez',
            'mensaje' => 'Check out our seo marketing service at www.spam.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_missing_nombre_fails_validation(): void
    {
        $response = $this->post(route('leads.store'), [
            'email' => 'juan@example.com',
        ]);

        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_invalid_email_fails_validation(): void
    {
        $response = $this->post(route('leads.store'), [
            'nombre' => 'Juan Perez',
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_nonexistent_propiedad_id_fails_validation(): void
    {
        $response = $this->post(route('leads.store'), [
            'nombre' => 'Juan Perez',
            'propiedad_id' => 999999,
        ]);

        $response->assertSessionHasErrors('propiedad_id');
        $this->assertDatabaseCount('leads', 0);
    }
}
