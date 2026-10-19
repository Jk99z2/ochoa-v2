<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Imagen;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RealEstateListingSchemaTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(array $overrides = []): Propiedad
    {
        $tipoOverride = $overrides['tipo'] ?? null;
        unset($overrides['tipo']);

        $municipio = Municipio::where('clave', 'MZO')->firstOrFail();
        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);
        $tipo = $tipoOverride ?? Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);

        return Propiedad::create(array_merge([
            'agente_id' => $agente->id,
            'municipio_id' => $municipio->id,
            'tipo_id' => $tipo->id,
            'titulo' => 'Casa frente al mar',
            'slug' => 'casa-frente-al-mar-'.uniqid(),
            'estado_mx' => 'Colima',
            'operacion' => 'venta',
            'estado' => 'disponible',
            'precio' => 2500000,
            'moneda' => 'MXN',
            'publicada' => true,
        ], $overrides));
    }

    public function test_basic_fields_and_offer(): void
    {
        $propiedad = $this->makePropiedad();

        $schema = $propiedad->realEstateListingSchema();

        $this->assertSame('RealEstateListing', $schema['@type']);
        $this->assertSame('Casa frente al mar', $schema['name']);
        $this->assertSame(route('propiedades.show', $propiedad->slug), $schema['url']);
        $this->assertSame(2500000.0, $schema['offers']['price']);
        $this->assertSame('MXN', $schema['offers']['priceCurrency']);
        $this->assertSame('https://schema.org/Sell', $schema['offers']['businessFunction']);
        $this->assertSame('https://schema.org/InStock', $schema['offers']['availability']);
    }

    public function test_renta_maps_to_lease_out(): void
    {
        $schema = $this->makePropiedad(['operacion' => 'renta'])->realEstateListingSchema();

        $this->assertSame('https://schema.org/LeaseOut', $schema['offers']['businessFunction']);
    }

    public function test_venta_renta_omits_business_function(): void
    {
        $schema = $this->makePropiedad(['operacion' => 'venta_renta'])->realEstateListingSchema();

        $this->assertArrayNotHasKey('businessFunction', $schema['offers']);
    }

    public function test_estado_maps_to_availability(): void
    {
        $vendida = $this->makePropiedad(['estado' => 'vendida'])->realEstateListingSchema();
        $apartada = $this->makePropiedad(['estado' => 'apartada'])->realEstateListingSchema();

        $this->assertSame('https://schema.org/SoldOut', $vendida['offers']['availability']);
        $this->assertSame('https://schema.org/LimitedAvailability', $apartada['offers']['availability']);
    }

    public function test_geo_and_address_are_included_when_location_is_not_hidden(): void
    {
        $propiedad = $this->makePropiedad(['lat' => 19.1233732, 'lng' => -104.3376828, 'ocultar_direccion' => false]);

        $schema = $propiedad->realEstateListingSchema();

        $this->assertSame([
            '@type' => 'GeoCoordinates',
            'latitude' => 19.1233732,
            'longitude' => -104.3376828,
        ], $schema['about']['geo']);
        $this->assertSame('Manzanillo', $schema['about']['address']['addressLocality']);
    }

    public function test_geo_is_dropped_when_ocultar_direccion_is_true(): void
    {
        $propiedad = $this->makePropiedad(['lat' => 19.1233732, 'lng' => -104.3376828, 'ocultar_direccion' => true]);

        $schema = $propiedad->realEstateListingSchema();

        $this->assertArrayNotHasKey('geo', $schema['about']);
        // City-level address is still fine to show - the visible page shows it too, hidden or not.
        $this->assertArrayHasKey('address', $schema['about']);
    }

    public function test_street_address_is_never_included_since_the_page_never_shows_it(): void
    {
        $propiedad = $this->makePropiedad(['calle' => 'Calle Secreta 123', 'colonia' => 'Las Brisas']);

        $schema = $propiedad->realEstateListingSchema();

        $this->assertArrayNotHasKey('streetAddress', $schema['about']['address'] ?? []);
        $json = json_encode($schema);
        $this->assertStringNotContainsString('Calle Secreta', $json);
    }

    public function test_accommodation_type_is_inferred_from_tipo_name(): void
    {
        $casa = $this->makePropiedad(['tipo' => Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1])]);
        $depa = $this->makePropiedad(['tipo' => Tipo::create(['nombre' => 'Departamento', 'slug' => 'depa-'.uniqid(), 'orden' => 2])]);
        $terreno = $this->makePropiedad(['tipo' => Tipo::create(['nombre' => 'Terreno', 'slug' => 'terreno-'.uniqid(), 'orden' => 3])]);

        $this->assertSame('SingleFamilyResidence', $casa->realEstateListingSchema()['about']['@type']);
        $this->assertSame('Apartment', $depa->realEstateListingSchema()['about']['@type']);
        $this->assertSame('Place', $terreno->realEstateListingSchema()['about']['@type']);
    }

    public function test_images_are_included_as_full_size_urls_capped_at_eight(): void
    {
        $propiedad = $this->makePropiedad();

        for ($i = 0; $i < 10; $i++) {
            Imagen::create(['propiedad_id' => $propiedad->id, 'path' => "propiedades/test-{$i}.jpg", 'orden' => $i]);
        }

        $schema = $propiedad->fresh('imagenes')->realEstateListingSchema();

        $this->assertCount(8, $schema['image']);
        $this->assertStringContainsString('/storage/propiedades/test-0.jpg', $schema['image'][0]);
    }

    public function test_detail_page_embeds_the_schema(): void
    {
        $propiedad = $this->makePropiedad();

        $html = $this->get(route('propiedades.show', $propiedad->slug))->getContent();

        $this->assertStringContainsString('"@type":"RealEstateListing"', $html);
    }

    public function test_per_m2_price_adds_a_unit_price_specification(): void
    {
        $schema = $this->makePropiedad(['precio' => 500, 'precio_por_m2' => true])->realEstateListingSchema();

        $this->assertSame('MTK', $schema['offers']['priceSpecification']['unitCode']);
        $this->assertSame(500.0, $schema['offers']['priceSpecification']['price']);
    }

    public function test_total_price_has_no_unit_price_specification(): void
    {
        $this->assertArrayNotHasKey('priceSpecification', $this->makePropiedad()->realEstateListingSchema()['offers']);
    }
}
