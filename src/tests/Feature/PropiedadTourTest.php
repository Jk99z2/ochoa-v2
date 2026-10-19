<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use ZipArchive;

class PropiedadTourTest extends TestCase
{
    use RefreshDatabase;

    private function storeZip(array $files): string
    {
        $path = 'tour-uploads/tour.zip';
        $zip = new ZipArchive;
        $zip->open(Storage::disk('local')->path($path), ZipArchive::CREATE);
        foreach ($files as $name => $contents) {
            $zip->addFromString($name, $contents);
        }
        $zip->close();

        return $path;
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('public');
        Storage::disk('local')->makeDirectory('tour-uploads');
    }

    public function test_extracts_tour_from_its_folder_and_skips_unsafe_files(): void
    {
        $zip = $this->storeZip([
            'RV Tour/index.htm' => '<html></html>',
            'RV Tour/lib/tdvplayer.js' => 'js',
            'RV Tour/media/pano_f.jpeg' => 'jpg',
            'RV Tour/shell.php' => '<?php system($_GET["c"]);',
            'RV Tour/.htaccess' => 'x',
        ]);

        $url = Propiedad::instalarTour($zip);

        $this->assertMatchesRegularExpression('#/storage/tours/[0-9a-f-]{36}/index\.htm$#', $url);
        $dir = 'tours/'.basename(dirname($url));
        Storage::disk('public')->assertExists([$dir.'/index.htm', $dir.'/lib/tdvplayer.js', $dir.'/media/pano_f.jpeg']);
        Storage::disk('public')->assertMissing([$dir.'/shell.php', $dir.'/.htaccess']);
        Storage::disk('local')->assertMissing($zip);
    }

    public function test_rejects_zip_without_index_page(): void
    {
        $zip = $this->storeZip(['media/pano.jpeg' => 'jpg']);

        $this->expectException(ValidationException::class);

        Propiedad::instalarTour($zip);
    }

    public function test_skips_symlinks_inside_the_archive(): void
    {
        $tar = Storage::disk('local')->path('tour-uploads/tour.tar');
        $src = sys_get_temp_dir().'/tour-symlink-'.uniqid();
        mkdir($src);
        file_put_contents($src.'/index.htm', '<html></html>');
        symlink('/etc/passwd', $src.'/robado.txt');
        exec('bsdtar -cf '.escapeshellarg($tar).' -C '.escapeshellarg($src).' index.htm robado.txt');

        $url = Propiedad::instalarTour('tour-uploads/tour.tar');

        $dir = 'tours/'.basename(dirname($url));
        Storage::disk('public')->assertExists($dir.'/index.htm');
        Storage::disk('public')->assertMissing($dir.'/robado.txt');
    }

    private function installTour(): string
    {
        return Propiedad::instalarTour($this->storeZip(['index.htm' => '<html></html>']));
    }

    private function tourDir(string $url): string
    {
        return 'tours/'.basename(dirname($url));
    }

    private function makePropiedad(array $overrides = []): Propiedad
    {
        return Propiedad::create(array_merge([
            'agente_id' => Agente::create(['nombre' => 'Agente', 'email' => 'agente-'.uniqid().'@example.com'])->id,
            'municipio_id' => Municipio::where('clave', 'MZO')->firstOrFail()->id,
            'tipo_id' => Tipo::create(['nombre' => 'Terreno', 'slug' => 'terreno-'.uniqid(), 'orden' => 1])->id,
            'titulo' => 'Terreno',
            'slug' => 'terreno-'.uniqid(),
            'operacion' => 'venta',
            'precio' => 100000,
        ], $overrides));
    }

    public function test_replacing_a_tour_deletes_the_old_folder(): void
    {
        $viejo = $this->installTour();
        $propiedad = $this->makePropiedad(['tour_url' => $viejo]);

        $propiedad->update(['tour_url' => $this->installTour()]);

        Storage::disk('public')->assertMissing($this->tourDir($viejo).'/index.htm');
        Storage::disk('public')->assertExists($this->tourDir($propiedad->tour_url).'/index.htm');
    }

    public function test_soft_delete_keeps_the_tour_and_force_delete_removes_it(): void
    {
        $url = $this->installTour();
        $propiedad = $this->makePropiedad(['tour_url' => $url]);

        $propiedad->delete();
        Storage::disk('public')->assertExists($this->tourDir($url).'/index.htm');

        $propiedad->forceDelete();
        Storage::disk('public')->assertMissing($this->tourDir($url).'/index.htm');
    }

    public function test_a_tour_still_used_by_another_property_is_kept(): void
    {
        $url = $this->installTour();
        $propiedad = $this->makePropiedad(['tour_url' => $url]);
        $this->makePropiedad(['tour_url' => $url]);

        $propiedad->update(['tour_url' => null]);

        Storage::disk('public')->assertExists($this->tourDir($url).'/index.htm');
    }

    public function test_external_tour_urls_are_never_touched(): void
    {
        $url = $this->installTour();
        $ajena = 'https://otro-sitio.com/storage/'.$this->tourDir($url).'/index.htm';
        $propiedad = $this->makePropiedad(['tour_url' => $ajena]);

        $propiedad->update(['tour_url' => null]);

        Storage::disk('public')->assertExists($this->tourDir($url).'/index.htm');
    }
}
