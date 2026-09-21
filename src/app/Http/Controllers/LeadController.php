<?php

namespace App\Http\Controllers;

use App\Mail\NewLeadMail;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (! empty($request->input('website'))) {
            return $this->genericSuccess();
        }

        $formTime = (int) $request->input('form_time', 0);
        if ($formTime > 0 && (time() - $formTime) < 3) {
            return back()->withErrors(['nombre' => 'Por favor intenta de nuevo.'])->withInput();
        }

        $spamText = strtolower($request->input('mensaje', '').' '.$request->input('nombre', ''));
        if (preg_match('#https?://|www\.|unsubscribe|seo\s|marketing\s+service|\bsms\b|lead\s+generation#i', $spamText)) {
            return $this->genericSuccess();
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:120',
            'email' => 'nullable|email|max:120',
            'telefono' => 'nullable|string|max:30',
            'mensaje' => 'nullable|string|max:2000',
            'propiedad_id' => 'nullable|exists:propiedades,id',
            'agente_id' => 'nullable|exists:agentes,id',
        ]);

        $lead = Lead::create([
            'propiedad_id' => $validated['propiedad_id'] ?? null,
            'agente_id' => $validated['agente_id'] ?? null,
            'nombre' => $validated['nombre'],
            'email' => $validated['email'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'mensaje' => $validated['mensaje'] ?? null,
            'origen' => 'formulario',
            'estatus' => 'nuevo',
        ]);

        $this->notifyRecipient($lead);

        return $this->genericSuccess();
    }

    /**
     * Sent after the response so the visitor never waits on SMTP, and a mail
     * failure is reported without losing the lead (already saved).
     */
    private function notifyRecipient(Lead $lead): void
    {
        $to = $lead->notificationEmail();

        if (! $to) {
            return;
        }

        defer(fn () => Mail::to($to)->send(new NewLeadMail($lead)));
    }

    private function genericSuccess(): RedirectResponse
    {
        return back()->with('success', 'Gracias por tu mensaje. Nos pondremos en contacto contigo pronto.');
    }
}
