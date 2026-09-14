<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(bool $publicada): Propiedad
    {
        $municipio = Municipio::where('clave', 'MZO')->firstOrFail();
        $tipo = Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);
        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);

        return Propiedad::create([
            'agente_id' => $agente->id,
            'municipio_id' => $municipio->id,
            'tipo_id' => $tipo->id,
            'titulo' => 'Propiedad de prueba',
            'slug' => 'propiedad-de-prueba-'.uniqid(),
            'operacion' => 'venta',
            'precio' => 100000,
            'publicada' => $publicada,
        ]);
    }

    public function test_sitemap_lists_home_and_listing_urls(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee(url('/'), false);
        $response->assertSee(url('/propiedades'), false);
    }

    public function test_sitemap_includes_published_properties_only(): void
    {
        $published = $this->makePropiedad(publicada: true);
        $unpublished = $this->makePropiedad(publicada: false);

        $response = $this->get('/sitemap.xml');

        $response->assertSee(route('propiedades.show', $published->slug), false);
        $response->assertDontSee(route('propiedades.show', $unpublished->slug), false);
    }

    public function test_robots_txt_points_to_the_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('Sitemap: '.url('/sitemap.xml'), false);
    }
}
