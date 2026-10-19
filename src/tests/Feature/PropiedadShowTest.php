<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropiedadShowTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(array $overrides = []): Propiedad
    {
        $municipio = Municipio::where('clave', 'MZO')->firstOrFail();
        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);

        return Propiedad::create(array_merge([
            'agente_id' => $agente->id,
            'municipio_id' => $municipio->id,
            'tipo_id' => Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1])->id,
            'titulo' => 'Casa frente al mar',
            'slug' => 'casa-frente-al-mar-'.uniqid(),
            'operacion' => 'venta',
            'precio' => 100000,
            'publicada' => true,
        ], $overrides));
    }

    public function test_canonical_points_to_the_clean_property_url(): void
    {
        $propiedad = $this->makePropiedad();

        $response = $this->get('/propiedades/'.$propiedad->slug);

        $response->assertSee(
            '<link rel="canonical" href="'.url('/propiedades/'.$propiedad->slug).'">',
            false,
        );
    }

    public function test_canonical_ignores_the_referring_agent_query_param(): void
    {
        $propiedad = $this->makePropiedad();
        $otroAgente = Agente::create(['nombre' => 'Otro', 'email' => 'otro-'.uniqid().'@example.com']);

        $response = $this->get('/propiedades/'.$propiedad->slug.'?agente='.$otroAgente->id);

        $response->assertSee(
            '<link rel="canonical" href="'.url('/propiedades/'.$propiedad->slug).'">',
            false,
        );
    }

    public function test_embeds_virtual_tour_when_set(): void
    {
        $propiedad = $this->makePropiedad(['tour_url' => 'https://example.com/tours/abc/index.htm']);

        $this->get('/propiedades/'.$propiedad->slug)
            ->assertSee('src="https://example.com/tours/abc/index.htm"', false);
    }

    public function test_omits_virtual_tour_when_not_set(): void
    {
        $propiedad = $this->makePropiedad();

        $this->get('/propiedades/'.$propiedad->slug)->assertDontSee('Tour virtual 360°');
    }

    public function test_per_m2_price_shows_unit_and_total(): void
    {
        $propiedad = $this->makePropiedad(['precio' => 500, 'precio_por_m2' => true, 'm2_terreno' => 1000]);

        $this->get('/propiedades/'.$propiedad->slug)
            ->assertSee('/ m²')
            ->assertSee('Total: $500,000 MXN');
    }
}
