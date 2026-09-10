<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $municipios = DB::table('municipios')->pluck('id', 'clave');
        $municipiosByName = DB::table('municipios')->get()->keyBy(fn ($m) => Str::lower(trim($m->nombre)));
        $manzanilloId = $municipios['MZO'];

        // Match each property's free-text `ciudad` to a real municipio by
        // name (case-insensitive). Falls back to Manzanillo - matching the
        // column's original default - for anything that doesn't match one
        // of the 10 official municipios (verified against real production
        // data before writing this: as of 2026-09-09, prod has exactly one
        // property, ciudad = "Manzanillo", an exact match).
        DB::table('propiedades')->orderBy('id')->select('id', 'ciudad')->get()->each(function ($propiedad) use ($municipiosByName, $manzanilloId) {
            $key = Str::lower(trim($propiedad->ciudad ?? ''));
            $municipioId = $municipiosByName[$key]->id ?? $manzanilloId;

            DB::table('propiedades')->where('id', $propiedad->id)->update([
                'municipio_id' => $municipioId,
            ]);
        });

        // Assign clave (MZO-0001 style) to existing properties, sequential
        // per municipio in creation order - same format the Propiedad model
        // uses for new records going forward.
        $counters = [];
        DB::table('propiedades')->orderBy('created_at')->orderBy('id')->select('id', 'municipio_id')->get()
            ->each(function ($propiedad) use (&$counters, $municipios) {
                $counters[$propiedad->municipio_id] = ($counters[$propiedad->municipio_id] ?? 0) + 1;
                $clave = $municipios->search($propiedad->municipio_id);

                DB::table('propiedades')->where('id', $propiedad->id)->update([
                    'clave' => $clave.'-'.str_pad((string) $counters[$propiedad->municipio_id], 4, '0', STR_PAD_LEFT),
                ]);
            });

        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropIndex('propiedades_ciudad_colonia_index');
        });

        Schema::table('propiedades', function (Blueprint $table) {
            $table->foreignId('municipio_id')->nullable(false)->change();
            $table->string('clave', 20)->nullable(false)->change();
            $table->dropColumn('ciudad');
        });

        Schema::table('propiedades', function (Blueprint $table) {
            $table->index(['municipio_id', 'colonia']);
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropIndex(['municipio_id', 'colonia']);
        });

        Schema::table('propiedades', function (Blueprint $table) {
            $table->string('ciudad', 120)->nullable()->default('Manzanillo');
            $table->foreignId('municipio_id')->nullable()->change();
            $table->string('clave', 20)->nullable()->change();
        });

        DB::table('propiedades')->orderBy('id')->select('id', 'municipio_id')->get()->each(function ($propiedad) {
            $nombre = DB::table('municipios')->where('id', $propiedad->municipio_id)->value('nombre');

            DB::table('propiedades')->where('id', $propiedad->id)->update([
                'ciudad' => $nombre,
            ]);
        });

        Schema::table('propiedades', function (Blueprint $table) {
            $table->index(['ciudad', 'colonia']);
        });
    }
};
