<?php

namespace App\Console\Commands;

use App\Models\Imagen;
use Illuminate\Console\Command;

class BackfillImagenThumbnails extends Command
{
    protected $signature = "imagenes:backfill-thumbnails";

    protected $description = "Generate the missing -thumb variant for property images uploaded before thumbnails existed";

    public function handle(): int
    {
        $imagenes = Imagen::all();
        $this->withProgressBar($imagenes, function (Imagen $imagen) {
            $imagen->generateThumbnail();
        });
        $this->newLine(2);

        $this->info("Processed {$imagenes->count()} image(s).");

        return self::SUCCESS;
    }
}
