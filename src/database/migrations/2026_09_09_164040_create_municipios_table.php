<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $t) {
            $t->id();
            $t->string('nombre', 60)->unique();
            $t->string('clave', 3)->unique();
            $t->unsignedTinyInteger('orden')->default(0);
        });

        // The 10 official municipios of Colima, Mexico - a fixed, closed set.
        DB::table('municipios')->insert([
            ['nombre' => 'Armería', 'clave' => 'ARM', 'orden' => 1],
            ['nombre' => 'Colima', 'clave' => 'COL', 'orden' => 2],
            ['nombre' => 'Comala', 'clave' => 'COM', 'orden' => 3],
            ['nombre' => 'Coquimatlán', 'clave' => 'CQT', 'orden' => 4],
            ['nombre' => 'Cuauhtémoc', 'clave' => 'CUA', 'orden' => 5],
            ['nombre' => 'Ixtlahuacán', 'clave' => 'IXT', 'orden' => 6],
            ['nombre' => 'Manzanillo', 'clave' => 'MZO', 'orden' => 7],
            ['nombre' => 'Minatitlán', 'clave' => 'MIN', 'orden' => 8],
            ['nombre' => 'Tecomán', 'clave' => 'TEC', 'orden' => 9],
            ['nombre' => 'Villa de Álvarez', 'clave' => 'VDA', 'orden' => 10],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
