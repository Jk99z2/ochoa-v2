<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Imagen;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImagenThumbnailTest extends TestCase
{
    use RefreshDatabase;

    private function makePropiedad(): Propiedad
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
            'publicada' => true,
        ]);
    }

    private function storeUploadedImage(string $filename = 'foto.jpg'): string
    {
        $path = 'propiedades/'.$filename;
        $file = UploadedFile::fake()->image($filename, 1600, 1200);
        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        return $path;
    }

    public function test_creating_an_imagen_generates_a_thumbnail(): void
    {
        Storage::fake('public');
        $path = $this->storeUploadedImage();

        $imagen = Imagen::create([
            'propiedad_id' => $this->makePropiedad()->id,
            'path' => $path,
        ]);

        Storage::disk('public')->assertExists($imagen->thumbPath());
    }

    public function test_thumb_url_falls_back_to_the_original_when_no_thumbnail_exists(): void
    {
        Storage::fake('public');

        $imagen = new Imagen(['path' => 'propiedades/missing.jpg']);

        $this->assertSame(Storage::disk('public')->url('propiedades/missing.jpg'), $imagen->thumb_url);
    }

    public function test_deleting_an_imagen_removes_its_thumbnail(): void
    {
        Storage::fake('public');
        $path = $this->storeUploadedImage();

        $imagen = Imagen::create([
            'propiedad_id' => $this->makePropiedad()->id,
            'path' => $path,
        ]);
        $thumbPath = $imagen->thumbPath();
        Storage::disk('public')->assertExists($thumbPath);

        $imagen->delete();

        Storage::disk('public')->assertMissing($thumbPath);
    }
}
