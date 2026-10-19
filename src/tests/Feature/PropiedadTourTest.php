<?php

namespace Tests\Feature;

use App\Models\Propiedad;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use ZipArchive;

class PropiedadTourTest extends TestCase
{
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
}
