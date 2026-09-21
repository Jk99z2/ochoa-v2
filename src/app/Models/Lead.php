<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        "propiedad_id",
        "agente_id",
        "nombre",
        "email",
        "telefono",
        "mensaje",
        "origen",
        "estatus",
    ];

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class);
    }

    /**
     * Who should hear about this lead: the agent it was addressed to, else the
     * property's agent, else the office contact address from the site settings.
     */
    public function notificationEmail(): ?string
    {
        foreach ([$this->agente, $this->propiedad?->agente] as $agente) {
            if ($agente?->activo && $agente->email) {
                return $agente->email;
            }
        }

        return Configuracion::actual()->email_contacto ?: null;
    }
}
