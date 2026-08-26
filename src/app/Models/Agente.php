<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agente extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "nombre",
        "email",
        "telefono",
        "whatsapp",
        "foto",
        "activo",
    ];

    protected $casts = [
        "activo" => "boolean",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function propiedades(): HasMany
    {
        return $this->hasMany(Propiedad::class);
    }
}
