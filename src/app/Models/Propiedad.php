<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propiedad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "propiedades";

    protected $fillable = [
        "agente_id", "tipo_id", "titulo", "slug", "descripcion", "operacion",
        "precio", "moneda", "mantenimiento", "estado", "publicada", "destacada",
        "m2_terreno", "m2_construccion", "recamaras", "banios", "niveles",
        "estacionamientos", "antiguedad", "calle", "colonia", "ciudad",
        "estado_mx", "cp", "lat", "lng", "ocultar_direccion", "video_url",
        "tour_url", "expediente", "vistas", "published_at",
    ];

    protected $casts = [
        "precio" => "decimal:2",
        "mantenimiento" => "decimal:2",
        "publicada" => "boolean",
        "destacada" => "boolean",
        "ocultar_direccion" => "boolean",
        "m2_terreno" => "decimal:2",
        "m2_construccion" => "decimal:2",
        "banios" => "decimal:1",
        "lat" => "decimal:7",
        "lng" => "decimal:7",
        "published_at" => "datetime",
    ];

    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class);
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(Tipo::class);
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(Imagen::class)->orderBy("orden");
    }

    public function amenidades(): BelongsToMany
    {
        return $this->belongsToMany(Amenidad::class, "amenidad_propiedad");
    }
}
