<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropForeign(['agente_id']);
        });

        Schema::table('propiedades', function (Blueprint $table) {
            $table->foreignId('agente_id')->nullable()->change();
            $table->foreign('agente_id')->references('id')->on('agentes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->dropForeign(['agente_id']);
        });

        Schema::table('propiedades', function (Blueprint $table) {
            $table->foreignId('agente_id')->nullable(false)->change();
            $table->foreign('agente_id')->references('id')->on('agentes')->restrictOnDelete();
        });
    }
};
