<?php

namespace App\Mail;

use App\Filament\Resources\Leads\LeadResource;
use App\Models\Lead;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;

class NewLeadMail extends Mailable
{
    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        $titulo = $this->lead->propiedad?->titulo;

        return new Envelope(
            subject: $titulo ? "Nuevo lead: {$titulo}" : 'Nuevo lead desde el sitio web',
            replyTo: $this->lead->email ? [new Address($this->lead->email, $this->lead->nombre)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.new-lead',
            text: 'mail.new-lead-text',
            with: [
                'lead' => $this->lead,
                'adminUrl' => LeadResource::getUrl('edit', ['record' => $this->lead]),
            ],
        );
    }
}
