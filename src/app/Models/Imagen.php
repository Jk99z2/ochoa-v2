<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imagen extends Model
{
    use HasFactory;

    protected $table = "imagenes";

    protected $fillable = [
        "propiedad_id",
        "path",
        "alt",
        "orden",
        "principal",
    ];

    protected $casts = [
        "principal" => "boolean",
    ];

    protected static function booted(): void
    {
        static::saving(function (Imagen $imagen) {
            if ($imagen->principal) {
                static::where("propiedad_id", $imagen->propiedad_id)
                    ->where("id", "!=", $imagen->id)
                    ->update(["principal" => false]);
            }
        });
    }

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }
}
