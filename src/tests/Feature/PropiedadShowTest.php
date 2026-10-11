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
}
