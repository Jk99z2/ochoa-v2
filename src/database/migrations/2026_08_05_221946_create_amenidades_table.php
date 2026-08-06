<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amenidades', function (Blueprint $t) {
            $t->id();
            $t->string('nombre', 80);
            $t->string('slug', 80)->unique();
            $t->string('icono', 60)->nullable();
        });

        Schema::create('amenidad_propiedad', function (Blueprint $t) {
            $t->foreignId('propiedad_id')->constrained('propiedades')->cascadeOnDelete();
            $t->foreignId('amenidad_id')->constrained('amenidades')->cascadeOnDelete();
            $t->primary(['propiedad_id','amenidad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenidad_propiedad');
        Schema::dropIfExists('amenidades');
    }
};
