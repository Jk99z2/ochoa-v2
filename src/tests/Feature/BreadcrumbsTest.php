<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BreadcrumbsTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(array $overrides = []): Propiedad
    {
        $tipo = $overrides['tipo'] ?? Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);
        unset($overrides['tipo']);

        $municipio = Municipio::where('clave', 'MZO')->firstOrFail();
        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);

        return Propiedad::create(array_merge([
            'agente_id' => $agente->id,
            'municipio_id' => $municipio->id,
            'tipo_id' => $tipo->id,
            'titulo' => 'Casa frente al mar',
            'slug' => 'casa-frente-al-mar-'.uniqid(),
            'operacion' => 'venta',
            'precio' => 100000,
            'publicada' => true,
        ], $overrides));
    }

    public function test_listing_breadcrumb_without_a_tipo_filter(): void
    {
        $html = $this->get('/propiedades')->getContent();

        $this->assertStringContainsString('<span aria-current="page">Propiedades</span>', $html);

        $this->assertMatchesRegularExpression(
            '#"itemListElement":\[\{"@type":"ListItem","position":1,"name":"Inicio","item":"'.preg_quote(url('/'), '#').'"\},'
            .'\{"@type":"ListItem","position":2,"name":"Propiedades"\}\]#',
            $html,
        );
    }

    public function test_listing_breadcrumb_gains_a_third_level_when_filtered_by_tipo(): void
    {
        $tipo = Tipo::create(['nombre' => 'Departamentos', 'slug' => 'departamentos', 'orden' => 1]);

        $html = $this->get('/propiedades?tipo=departamentos')->getContent();

        $this->assertStringContainsString('<a href="'.route('propiedades.index').'">Propiedades</a>', $html);
        $this->assertStringContainsString('<span aria-current="page">Departamentos</span>', $html);
        $this->assertStringContainsString('"name":"Departamentos"', $html);
    }

    public function test_detail_breadcrumb_includes_tipo_and_title(): void
    {
        $tipo = Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-breadcrumb', 'orden' => 1]);
        $propiedad = $this->makePropiedad(['tipo' => $tipo, 'titulo' => 'Villa del Mar']);

        $html = $this->get(route('propiedades.show', $propiedad->slug))->getContent();

        $this->assertStringContainsString('<a href="'.url('/').'">Inicio</a>', $html);
        $this->assertStringContainsString('<a href="'.route('propiedades.index').'">Propiedades</a>', $html);
        $this->assertStringContainsString(
            '<a href="'.route('propiedades.index', ['tipo' => 'casa-breadcrumb']).'">Casa</a>',
            $html,
        );
        $this->assertStringContainsString('<span aria-current="page">Villa del Mar</span>', $html);
    }

    public function test_detail_breadcrumb_json_ld_has_no_item_for_the_current_page(): void
    {
        $propiedad = $this->makePropiedad(['titulo' => 'Villa del Mar']);

        $html = $this->get(route('propiedades.show', $propiedad->slug))->getContent();

        // tipo_id is required, so every property has 4 crumb levels: Inicio, Propiedades, Tipo, title.
        $this->assertStringContainsString('{"@type":"ListItem","position":4,"name":"Villa del Mar"}', $html);
    }
}
