<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Propiedad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'propiedades';

    protected $fillable = [
        'agente_id', 'municipio_id', 'tipo_id', 'titulo', 'slug', 'descripcion', 'operacion',
        'precio', 'moneda', 'precio_por_m2', 'mantenimiento', 'estado', 'publicada', 'destacada',
        'm2_terreno', 'm2_construccion', 'recamaras', 'banios', 'niveles',
        'estacionamientos', 'antiguedad', 'calle', 'colonia',
        'estado_mx', 'cp', 'lat', 'lng', 'ocultar_direccion', 'video_url',
        'tour_url', 'expediente', 'vistas', 'published_at',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'mantenimiento' => 'decimal:2',
        'precio_por_m2' => 'boolean',
        'publicada' => 'boolean',
        'destacada' => 'boolean',
        'ocultar_direccion' => 'boolean',
        'm2_terreno' => 'decimal:2',
        'm2_construccion' => 'decimal:2',
        'banios' => 'decimal:1',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Propiedad $propiedad) {
            if ($propiedad->clave || ! $propiedad->municipio_id) {
                return;
            }

            $propiedad->clave = DB::transaction(function () use ($propiedad) {
                $municipio = Municipio::whereKey($propiedad->municipio_id)->lockForUpdate()->firstOrFail();

                $next = static::withTrashed()
                    ->where('municipio_id', $municipio->id)
                    ->lockForUpdate()
                    ->count() + 1;

                return $municipio->clave.'-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
            });
        });

        // Tours are 40-95 MB each: drop the old folder when it's replaced or the
        // property is gone for good (a soft delete can still be restored).
        static::updated(function (Propiedad $propiedad) {
            if ($propiedad->wasChanged('tour_url')) {
                static::borrarTour($propiedad->getOriginal('tour_url'));
            }
        });

        static::forceDeleted(fn (Propiedad $propiedad) => static::borrarTour($propiedad->tour_url));
    }

    /**
     * Deletes a tour folder created by instalarTour(), unless another property
     * (deleted ones included) still points at it. Any other URL, like a tour
     * hosted elsewhere, is left alone.
     */
    private static function borrarTour(?string $url): void
    {
        $public = Storage::disk('public');
        $prefijo = preg_quote($public->url('tours/'), '#');

        if (! $url || ! preg_match('#^'.$prefijo.'([0-9a-f-]{36})/#', $url, $m)) {
            return;
        }

        $dir = 'tours/'.$m[1];
        $enUso = static::withTrashed()->where('tour_url', 'like', $public->url($dir).'/%')->exists();

        if (! $enUso) {
            $public->deleteDirectory($dir);
        }
    }

    public function agente(): BelongsTo
    {
        return $this->belongsTo(Agente::class);
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(Tipo::class);
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(Imagen::class)->orderBy('orden');
    }

    public function amenidades(): BelongsToMany
    {
        return $this->belongsToMany(Amenidad::class, 'amenidad_propiedad');
    }

    /**
     * JSON-LD for this listing (schema.org RealEstateListing wrapping an
     * Accommodation subtype + an Offer). Only represents what the detail
     * page actually shows a visitor - Google requires structured data to
     * match visible content, and `ocultar_direccion` is a deliberate privacy
     * choice the agent made, so it's respected here exactly like the page
     * respects it (precise geo dropped, street address never included,
     * since it's never shown even when the address isn't hidden).
     */
    public function realEstateListingSchema(): array
    {
        $about = array_filter([
            '@type' => $this->accommodationSchemaType(),
            'name' => $this->titulo,
        ]);

        if ($this->municipio) {
            $about['address'] = [
                '@type' => 'PostalAddress',
                'addressLocality' => $this->municipio->nombre,
                'addressRegion' => $this->estado_mx,
                'addressCountry' => 'MX',
            ];
        }

        if ($this->lat !== null && $this->lng !== null && ! $this->ocultar_direccion) {
            $about['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $this->lat,
                'longitude' => (float) $this->lng,
            ];
        }

        if ($this->recamaras) {
            $about['numberOfBedrooms'] = $this->recamaras;
        }

        if ($this->banios > 0) { // decimal casts are strings, and "0.0" is truthy
            $about['numberOfBathroomsTotal'] = (float) $this->banios;
        }

        if ($this->m2_construccion > 0) {
            $about['floorSize'] = [
                '@type' => 'QuantitativeValue',
                'value' => (float) $this->m2_construccion,
                'unitCode' => 'MTK',
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'RealEstateListing',
            'name' => $this->titulo,
            'url' => route('propiedades.show', $this->slug),
            'about' => $about,
            'offers' => array_filter([
                '@type' => 'Offer',
                'price' => (float) $this->precio,
                'priceCurrency' => $this->moneda,
                'availability' => $this->availabilitySchemaType(),
                'businessFunction' => match ($this->operacion) {
                    'venta' => 'https://schema.org/Sell',
                    'renta' => 'https://schema.org/LeaseOut',
                    default => null, // venta_renta: one price can't represent both unambiguously
                },
                'priceSpecification' => $this->precio_por_m2 ? [
                    '@type' => 'UnitPriceSpecification',
                    'price' => (float) $this->precio,
                    'priceCurrency' => $this->moneda,
                    'unitCode' => 'MTK',
                ] : null,
            ]),
        ];

        if ($this->descripcion) {
            $schema['description'] = $this->descripcion;
        }

        if ($this->imagenes->isNotEmpty()) {
            $schema['image'] = $this->imagenes->take(8)->map(fn (Imagen $imagen) => Storage::url($imagen->path))->values()->all();
        }

        if ($this->published_at) {
            $schema['datePosted'] = $this->published_at->toDateString();
        }

        return $schema;
    }

    /**
     * Extracts a virtual tour export (3DVista, krpano, ...) uploaded to the
     * local disk as .zip or .rar into public storage and returns the URL of
     * its index page. bsdtar refuses absolute and "../" paths; on top of that
     * only regular files with static web extensions are copied (no symlinks),
     * since public/storage is served by nginx, which would execute a .php file.
     */
    public static function instalarTour(string $archivoPath): string
    {
        $permitidas = [
            'htm', 'html', 'js', 'css', 'json', 'xml', 'txt',
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico', 'cur',
            'mp3', 'ogg', 'wav', 'm4a', 'mp4', 'webm', 'm3u8', 'ts',
            'woff', 'woff2', 'ttf', 'otf',
        ];
        $local = Storage::disk('local');
        $error = fn (string $msg) => ValidationException::withMessages(['data.tour_zip' => $msg]);

        $tmp = 'tour-uploads/'.Str::uuid();
        $local->makeDirectory($tmp);

        try {
            if (Process::run(['bsdtar', '-xf', $local->path($archivoPath), '-C', $local->path($tmp)])->failed()) {
                throw $error('No se pudo abrir el archivo. Debe ser un .zip o .rar.');
            }

            $nombres = [];
            $archivos = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($local->path($tmp), \FilesystemIterator::SKIP_DOTS));
            foreach ($archivos as $archivo) {
                $nombre = substr($archivo->getPathname(), strlen($local->path($tmp)) + 1);
                if ($archivo->isLink() || ! $archivo->isFile() || str_starts_with($nombre, '__MACOSX/')
                    || ! in_array(strtolower($archivo->getExtension()), $permitidas, true)) {
                    continue;
                }
                $nombres[] = $nombre;
            }

            // The shallowest index page is the tour's root; exports are often archived inside a folder.
            $indices = array_filter($nombres, fn ($n) => preg_match('#(^|/)index\.html?$#i', $n));
            usort($indices, fn ($a, $b) => substr_count($a, '/') <=> substr_count($b, '/'));
            if (! $indices) {
                throw $error('El archivo no contiene un index.htm o index.html.');
            }
            $raiz = dirname($indices[0]) === '.' ? '' : dirname($indices[0]).'/';

            $dir = 'tours/'.Str::uuid();
            $public = Storage::disk('public');
            foreach ($nombres as $nombre) {
                if (str_starts_with($nombre, $raiz)) {
                    $public->writeStream($dir.'/'.substr($nombre, strlen($raiz)), $local->readStream($tmp.'/'.$nombre));
                }
            }
        } finally {
            $local->deleteDirectory($tmp);
        }
        $local->delete($archivoPath);

        return $public->url($dir.'/'.basename($indices[0]));
    }

    private function accommodationSchemaType(): string
    {
        $nombre = mb_strtolower($this->tipo?->nombre ?? '');

        return match (true) {
            str_contains($nombre, 'casa') => 'SingleFamilyResidence',
            str_contains($nombre, 'depa') || str_contains($nombre, 'apart') || str_contains($nombre, 'condo') => 'Apartment',
            default => 'Place', // terreno, local comercial, oficina, etc. - no closer schema.org fit
        };
    }

    private function availabilitySchemaType(): string
    {
        return match ($this->estado) {
            'disponible' => 'https://schema.org/InStock',
            'apartada' => 'https://schema.org/LimitedAvailability',
            'vendida', 'rentada' => 'https://schema.org/SoldOut',
            default => 'https://schema.org/InStock',
        };
    }
}
