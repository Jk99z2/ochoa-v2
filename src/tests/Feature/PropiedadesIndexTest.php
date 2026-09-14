<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropiedadesIndexTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(array $overrides = []): Propiedad
    {
        $municipio = Municipio::where('clave', $overrides['municipio_clave'] ?? 'MZO')->firstOrFail();
        unset($overrides['municipio_clave']);

        $tipo = $overrides['tipo'] ?? Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);
        unset($overrides['tipo']);

        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);

        return Propiedad::create(array_merge([
            'agente_id' => $agente->id,
            'municipio_id' => $municipio->id,
            'tipo_id' => $tipo->id,
            'titulo' => 'Propiedad de prueba',
            'slug' => 'propiedad-de-prueba-'.uniqid(),
            'operacion' => 'venta',
            'precio' => 100000,
            'publicada' => true,
        ], $overrides));
    }

    public function test_index_only_shows_published_properties(): void
    {
        $published = $this->makePropiedad(['publicada' => true]);
        $unpublished = $this->makePropiedad(['publicada' => false]);

        $response = $this->get('/propiedades');

        $response->assertViewHas('propiedades', function ($propiedades) use ($published, $unpublished) {
            return $propiedades->contains('id', $published->id)
                && ! $propiedades->contains('id', $unpublished->id);
        });
    }

    public function test_filters_by_tipo(): void
    {
        $casa = Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);
        $terreno = Tipo::create(['nombre' => 'Terreno', 'slug' => 'terreno-'.uniqid(), 'orden' => 2]);

        $match = $this->makePropiedad(['tipo' => $casa]);
        $other = $this->makePropiedad(['tipo' => $terreno]);

        $response = $this->get('/propiedades?tipo='.$casa->slug);

        $response->assertViewHas('propiedades', function ($propiedades) use ($match, $other) {
            return $propiedades->contains('id', $match->id)
                && ! $propiedades->contains('id', $other->id);
        });
    }

    public function test_filters_by_operacion(): void
    {
        $venta = $this->makePropiedad(['operacion' => 'venta']);
        $renta = $this->makePropiedad(['operacion' => 'renta']);

        $response = $this->get('/propiedades?operacion=renta');

        $response->assertViewHas('propiedades', function ($propiedades) use ($venta, $renta) {
            return $propiedades->contains('id', $renta->id)
                && ! $propiedades->contains('id', $venta->id);
        });
    }

    public function test_filters_by_price_range(): void
    {
        $cheap = $this->makePropiedad(['precio' => 50000]);
        $mid = $this->makePropiedad(['precio' => 150000]);
        $expensive = $this->makePropiedad(['precio' => 500000]);

        $response = $this->get('/propiedades?min_price=100000&max_price=200000');

        $response->assertViewHas('propiedades', function ($propiedades) use ($cheap, $mid, $expensive) {
            return $propiedades->contains('id', $mid->id)
                && ! $propiedades->contains('id', $cheap->id)
                && ! $propiedades->contains('id', $expensive->id);
        });
    }

    public function test_filters_by_municipio(): void
    {
        $manzanillo = $this->makePropiedad(['municipio_clave' => 'MZO']);
        $colima = $this->makePropiedad(['municipio_clave' => 'COL']);

        $response = $this->get('/propiedades?municipio=COL');

        $response->assertViewHas('propiedades', function ($propiedades) use ($manzanillo, $colima) {
            return $propiedades->contains('id', $colima->id)
                && ! $propiedades->contains('id', $manzanillo->id);
        });
    }

    public function test_paginates_results_nine_per_page(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->makePropiedad();
        }

        $firstPage = $this->get('/propiedades');
        $firstPage->assertViewHas('propiedades', function ($propiedades) {
            return $propiedades->count() === 9 && $propiedades->total() === 10;
        });

        $secondPage = $this->get('/propiedades?page=2');
        $secondPage->assertViewHas('propiedades', function ($propiedades) {
            return $propiedades->count() === 1;
        });
    }

    public function test_shows_empty_state_when_no_properties_match_filters(): void
    {
        $this->makePropiedad(['precio' => 100000]);

        $response = $this->get('/propiedades?min_price=999999');

        $response->assertSee('No se encontraron propiedades con esos filtros.');
    }
}
