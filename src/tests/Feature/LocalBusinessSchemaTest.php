<?php

namespace Tests\Feature;

use App\Models\Configuracion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalBusinessSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_minimal_config_produces_only_the_required_fields(): void
    {
        $schema = Configuracion::actual()->localBusinessSchema();

        $this->assertSame('https://schema.org', $schema['@context']);
        $this->assertSame('RealEstateAgent', $schema['@type']);
        $this->assertArrayNotHasKey('telephone', $schema);
        $this->assertArrayNotHasKey('email', $schema);
        $this->assertArrayNotHasKey('address', $schema);
        $this->assertArrayNotHasKey('geo', $schema);
        $this->assertArrayNotHasKey('sameAs', $schema);
    }

    public function test_office_phone_is_preferred_over_cell_phone(): void
    {
        $config = Configuracion::actual();
        $config->update(['telefono_oficina' => '3141111111', 'telefono_celular' => '3142222222']);

        $this->assertSame('3141111111', $config->localBusinessSchema()['telephone']);
    }

    public function test_falls_back_to_cell_phone_when_office_phone_is_missing(): void
    {
        $config = Configuracion::actual();
        $config->update(['telefono_oficina' => null, 'telefono_celular' => '3142222222']);

        $this->assertSame('3142222222', $config->localBusinessSchema()['telephone']);
    }

    public function test_address_and_geo_only_appear_once_set(): void
    {
        $config = Configuracion::actual();
        $config->update([
            'direccion' => 'Av. Mexico 100',
            'lat' => 19.1233732,
            'lng' => -104.3376828,
        ]);

        $schema = $config->localBusinessSchema();

        $this->assertSame([
            '@type' => 'PostalAddress',
            'streetAddress' => 'Av. Mexico 100',
            'addressLocality' => 'Manzanillo',
            'addressRegion' => 'Colima',
            'addressCountry' => 'MX',
        ], $schema['address']);

        $this->assertSame([
            '@type' => 'GeoCoordinates',
            'latitude' => 19.1233732,
            'longitude' => -104.3376828,
        ], $schema['geo']);
    }

    public function test_same_as_collects_only_the_profiles_that_are_set(): void
    {
        $config = Configuracion::actual();
        $config->update([
            'facebook_url' => 'https://facebook.com/ochoa',
            'instagram_url' => null,
            'whatsapp_numero' => '523141234567',
        ]);

        $schema = $config->localBusinessSchema();

        $this->assertSame([
            'https://facebook.com/ochoa',
            'https://wa.me/523141234567',
        ], $schema['sameAs']);
    }

    public function test_home_page_embeds_the_schema_as_valid_json_ld(): void
    {
        Configuracion::actual()->update(['email_contacto' => 'info@ochoa.test']);

        $html = $this->get('/')->getContent();

        $this->assertMatchesRegularExpression(
            '#<script type="application/ld\+json">(.*?)</script>#s',
            $html,
        );

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        $decoded = json_decode($m[1], true);

        $this->assertSame('RealEstateAgent', $decoded['@type']);
        $this->assertSame('info@ochoa.test', $decoded['email']);
    }
}
