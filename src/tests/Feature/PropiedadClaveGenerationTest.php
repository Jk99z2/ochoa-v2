<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropiedadClaveGenerationTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(Municipio $municipio, Tipo $tipo, Agente $agente): Propiedad
    {
        return Propiedad::create([
            'agente_id' => $agente->id,
            'municipio_id' => $municipio->id,
            'tipo_id' => $tipo->id,
            'titulo' => 'Propiedad de prueba',
            'slug' => 'propiedad-de-prueba-'.uniqid(),
            'operacion' => 'venta',
            'precio' => 100000,
        ]);
    }

    public function test_clave_is_generated_from_municipio_key_and_sequence(): void
    {
        $municipio = Municipio::where('clave', 'MZO')->firstOrFail();
        $tipo = Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);
        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);

        $propiedad = $this->makePropiedad($municipio, $tipo, $agente);

        $this->assertSame('MZO-0001', $propiedad->clave);
    }

    public function test_clave_sequence_increments_per_municipio_independently(): void
    {
        $mzo = Municipio::where('clave', 'MZO')->firstOrFail();
        $col = Municipio::where('clave', 'COL')->firstOrFail();
        $tipo = Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);
        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);

        $first = $this->makePropiedad($mzo, $tipo, $agente);
        $second = $this->makePropiedad($mzo, $tipo, $agente);
        $thirdOtherMunicipio = $this->makePropiedad($col, $tipo, $agente);

        $this->assertSame('MZO-0001', $first->clave);
        $this->assertSame('MZO-0002', $second->clave);
        $this->assertSame('COL-0001', $thirdOtherMunicipio->clave);
    }

    public function test_clave_is_never_overwritten_once_set(): void
    {
        $municipio = Municipio::where('clave', 'MZO')->firstOrFail();
        $tipo = Tipo::create(['nombre' => 'Casa', 'slug' => 'casa-'.uniqid(), 'orden' => 1]);
        $agente = Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com']);

        $propiedad = $this->makePropiedad($municipio, $tipo, $agente);
        $originalClave = $propiedad->clave;

        $propiedad->update(['titulo' => 'Titulo actualizado']);

        $this->assertSame($originalClave, $propiedad->fresh()->clave);
    }
}
