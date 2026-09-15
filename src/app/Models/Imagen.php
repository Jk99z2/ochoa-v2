<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;

class Imagen extends Model
{
    use HasFactory;

    const THUMB_WIDTH = 480;

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

        static::saved(function (Imagen $imagen) {
            if ($imagen->wasRecentlyCreated || $imagen->wasChanged("path")) {
                $imagen->generateThumbnail();
            }
        });

        static::deleted(function (Imagen $imagen) {
            Storage::disk("public")->delete($imagen->thumbPath());
        });
    }

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function thumbPath(): string
    {
        $info = pathinfo($this->path);
        $dir = ($info["dirname"] === ".") ? "" : $info["dirname"]."/";

        return $dir.$info["filename"]."-thumb.".$info["extension"];
    }

    public function generateThumbnail(): void
    {
        $disk = Storage::disk("public");

        if (! $disk->exists($this->path)) {
            return;
        }

        $image = ImageManager::gd()->read($disk->path($this->path));
        $image->scaleDown(width: self::THUMB_WIDTH);

        $disk->put($this->thumbPath(), (string) $image->encode(new AutoEncoder(quality: 82)));
    }

    protected function getThumbUrlAttribute(): string
    {
        $thumbPath = $this->thumbPath();

        return Storage::disk("public")->exists($thumbPath)
            ? Storage::url($thumbPath)
            : Storage::url($this->path);
    }
}
