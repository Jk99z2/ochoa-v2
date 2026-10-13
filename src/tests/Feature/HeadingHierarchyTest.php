<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Found via a Yoast SEO Checker report against production: the shared footer
 * used <h4> right after an <h2> with no <h3> in between (same bug repeated on
 * the listing page with its <h1> > <h3> "Filtrar" sidebar title). Search
 * engines read a skipped level as a sign of disorganized content.
 */
class HeadingHierarchyTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(array $overrides = []): Propiedad
    {
        $municipio = Municipio::where('clave', 'MZO')->firstOrFail();
        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);
        $tipo = Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);

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

    /**
     * No <hN> in the document may be more than one level deeper than the
     * highest level seen so far - the exact rule Yoast flags.
     */
    private function assertNoHeadingLevelIsSkipped(string $html): void
    {
        preg_match_all('/<h([1-6])[ >]/', $html, $matches);
        $levels = array_map('intval', $matches[1]);

        $seenMax = 0;
        foreach ($levels as $level) {
            if ($level > $seenMax + 1) {
                $this->fail("Heading jumps from h{$seenMax} straight to h{$level} (sequence: ".implode(',', $levels).')');
            }
            $seenMax = max($seenMax, $level);
        }

        $this->assertNotEmpty($levels, 'Expected at least one heading on the page.');
    }

    public function test_home_page_heading_hierarchy_has_no_gaps(): void
    {
        $this->makePropiedad();

        $this->assertNoHeadingLevelIsSkipped($this->get('/')->getContent());
    }

    public function test_listing_page_heading_hierarchy_has_no_gaps(): void
    {
        $this->makePropiedad();

        $this->assertNoHeadingLevelIsSkipped($this->get('/propiedades')->getContent());
    }

    public function test_detail_page_heading_hierarchy_has_no_gaps(): void
    {
        $propiedad = $this->makePropiedad();

        $this->assertNoHeadingLevelIsSkipped($this->get(route('propiedades.show', $propiedad->slug))->getContent());
    }
}
