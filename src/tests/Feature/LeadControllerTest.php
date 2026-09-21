<?php

namespace Tests\Feature;

use App\Mail\NewLeadMail;
use App\Models\Agente;
use App\Models\Configuracion;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

    private function makePropiedad(Agente $agente): Propiedad
    {
        return Propiedad::create([
            'agente_id' => $agente->id,
            'municipio_id' => Municipio::where('clave', 'MZO')->firstOrFail()->id,
            'tipo_id' => Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1])->id,
            'titulo' => 'Casa frente al mar',
            'slug' => 'casa-frente-al-mar-'.uniqid(),
            'operacion' => 'venta',
            'precio' => 100000,
            'publicada' => true,
        ]);
    }

    public function test_lead_notifies_the_agent_it_was_addressed_to(): void
    {
        Mail::fake();
        $agente = Agente::create(['nombre' => 'Ana', 'email' => 'ana@example.com']);

        $this->post(route('leads.store'), [
            'nombre' => 'Juan Perez',
            'email' => 'juan@example.com',
            'agente_id' => $agente->id,
        ]);

        Mail::assertSent(NewLeadMail::class, fn (NewLeadMail $mail) => $mail->hasTo('ana@example.com')
            && $mail->hasReplyTo('juan@example.com')
            && $mail->lead->nombre === 'Juan Perez');
    }

    public function test_lead_falls_back_to_the_property_agent(): void
    {
        Mail::fake();
        $agente = Agente::create(['nombre' => 'Ana', 'email' => 'ana@example.com']);
        $propiedad = $this->makePropiedad($agente);

        $this->post(route('leads.store'), [
            'nombre' => 'Juan Perez',
            'propiedad_id' => $propiedad->id,
        ]);

        Mail::assertSent(NewLeadMail::class, fn (NewLeadMail $mail) => $mail->hasTo('ana@example.com'));
    }

    public function test_lead_falls_back_to_office_email_when_agent_is_inactive(): void
    {
        Mail::fake();
        Configuracion::actual()->update(['email_contacto' => 'oficina@example.com']);
        $agente = Agente::create(['nombre' => 'Ana', 'email' => 'ana@example.com', 'activo' => false]);

        $this->post(route('leads.store'), [
            'nombre' => 'Juan Perez',
            'agente_id' => $agente->id,
        ]);

        Mail::assertSent(NewLeadMail::class, fn (NewLeadMail $mail) => $mail->hasTo('oficina@example.com'));
    }

    public function test_lead_is_saved_even_when_there_is_nobody_to_notify(): void
    {
        Mail::fake();

        $this->post(route('leads.store'), ['nombre' => 'Juan Perez']);

        Mail::assertNothingSent();
        $this->assertDatabaseHas('leads', ['nombre' => 'Juan Perez']);
    }

    public function test_rejected_spam_does_not_send_email(): void
    {
        Mail::fake();
        $agente = Agente::create(['nombre' => 'Ana', 'email' => 'ana@example.com']);

        $this->post(route('leads.store'), [
            'nombre' => 'Bot',
            'website' => 'http://spam.example.com',
            'agente_id' => $agente->id,
        ]);

        Mail::assertNothingSent();
    }

    public function test_notification_email_renders_lead_details(): void
    {
        $lead = \App\Models\Lead::create([
            'nombre' => 'Juan <b>Perez</b>',
            'email' => 'juan@example.com',
            'telefono' => '3141234567',
            'mensaje' => 'Me interesa & quiero visitarla',
        ]);

        $mail = new NewLeadMail($lead);

        $mail->assertSeeInHtml('Juan &lt;b&gt;Perez&lt;/b&gt;', false);
        $mail->assertSeeInHtml('3141234567');
        $mail->assertSeeInText('Me interesa & quiero visitarla', false);
        $mail->assertSeeInHtml('/admin/leads/'.$lead->id.'/edit', false);
    }
}
