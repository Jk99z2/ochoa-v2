<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            // Nullable for now - a follow-up migration backfills these from
            // the existing free-text `ciudad` column (matched against real
            // production data), then makes both required and drops `ciudad`.
            $table->foreignId('municipio_id')->nullable()->after('agente_id')->constrained()->restrictOnDelete();
            $table->string('clave', 20)->nullable()->unique()->after('municipio_id');
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropForeign(['municipio_id']);
            $table->dropColumn(['municipio_id', 'clave']);
        });
    }
};
