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
}
