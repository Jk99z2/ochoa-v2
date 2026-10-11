<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Configuracion extends Model
{
    protected $table = "configuracion";

    protected $guarded = [];

    public static function actual(): self
    {
        return static::firstOrCreate(["id" => 1]);
    }

    /**
     * JSON-LD for a schema.org RealEstateAgent: the site's local-SEO identity.
     * Every field is optional in the admin, so this only includes what's
     * actually filled in rather than emitting empty/null values.
     */
    public function localBusinessSchema(): array
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "RealEstateAgent",
            "@id" => url("/")."#organization",
            "name" => $this->nombre_sitio,
            "url" => url("/"),
            "areaServed" => "Manzanillo, Colima, México",
        ];

        if ($this->logo_path) {
            $schema["logo"] = Storage::url($this->logo_path);
            $schema["image"] = Storage::url($this->logo_path);
        }

        if ($telephone = $this->telefono_oficina ?: $this->telefono_celular) {
            $schema["telephone"] = $telephone;
        }

        if ($this->email_contacto) {
            $schema["email"] = $this->email_contacto;
        }

        if ($this->direccion) {
            $schema["address"] = [
                "@type" => "PostalAddress",
                "streetAddress" => $this->direccion,
                "addressLocality" => "Manzanillo",
                "addressRegion" => "Colima",
                "addressCountry" => "MX",
            ];
        }

        if ($this->lat !== null && $this->lng !== null) {
            $schema["geo"] = [
                "@type" => "GeoCoordinates",
                "latitude" => (float) $this->lat,
                "longitude" => (float) $this->lng,
            ];
        }

        $sameAs = array_filter([
            $this->facebook_url,
            $this->instagram_url,
            $this->whatsapp_numero ? "https://wa.me/".preg_replace("/[^0-9]/", "", $this->whatsapp_numero) : null,
        ]);

        if ($sameAs !== []) {
            $schema["sameAs"] = array_values($sameAs);
        }

        return $schema;
    }
}
