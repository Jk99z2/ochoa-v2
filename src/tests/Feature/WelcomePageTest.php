<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(array $overrides = []): Propiedad
    {
        $municipio = Municipio::where('clave', 'MZO')->firstOrFail();
        $tipo = Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);
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
            'destacada' => false,
        ], $overrides));
    }

    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('welcome');
    }

    public function test_home_page_only_shows_published_properties(): void
    {
        $published = $this->makePropiedad(['titulo' => 'Publicada', 'publicada' => true]);
        $unpublished = $this->makePropiedad(['titulo' => 'Sin publicar', 'publicada' => false]);

        $response = $this->get('/');

        $response->assertViewHas('nuevas', function ($nuevas) use ($published, $unpublished) {
            return $nuevas->contains('id', $published->id)
                && ! $nuevas->contains('id', $unpublished->id);
        });
    }

    public function test_home_page_destacadas_falls_back_to_any_published_when_none_are_featured(): void
    {
        $propiedad = $this->makePropiedad(['destacada' => false, 'publicada' => true]);

        $response = $this->get('/');

        $response->assertViewHas('destacadas', function ($destacadas) use ($propiedad) {
            return $destacadas->contains('id', $propiedad->id);
        });
    }

    public function test_home_page_destacadas_prefers_featured_properties_when_available(): void
    {
        $featured = $this->makePropiedad(['destacada' => true, 'publicada' => true]);
        $regular = $this->makePropiedad(['destacada' => false, 'publicada' => true]);

        $response = $this->get('/');

        $response->assertViewHas('destacadas', function ($destacadas) use ($featured, $regular) {
            return $destacadas->contains('id', $featured->id)
                && ! $destacadas->contains('id', $regular->id);
        });
    }

    public function test_home_page_recomendadas_only_includes_featured_properties(): void
    {
        $featured = $this->makePropiedad(['destacada' => true, 'publicada' => true]);
        $regular = $this->makePropiedad(['destacada' => false, 'publicada' => true]);

        $response = $this->get('/');

        $response->assertViewHas('recomendadas', function ($recomendadas) use ($featured, $regular) {
            return $recomendadas->contains('id', $featured->id)
                && ! $recomendadas->contains('id', $regular->id);
        });
    }

    public function test_home_page_orders_nuevas_by_most_recent_first(): void
    {
        $older = $this->makePropiedad(['titulo' => 'Vieja']);
        $older->forceFill(['created_at' => now()->subDays(2)])->save();

        $newer = $this->makePropiedad(['titulo' => 'Nueva']);
        $newer->forceFill(['created_at' => now()])->save();

        $response = $this->get('/');

        $response->assertViewHas('nuevas', function ($nuevas) use ($older, $newer) {
            return $nuevas->first()->id === $newer->id
                && $nuevas->last()->id === $older->id;
        });
    }
}
