<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propiedades', function (Blueprint $t) {
            $t->id();
            $t->foreignId('agente_id')->constrained()->restrictOnDelete();
            $t->foreignId('tipo_id')->constrained()->restrictOnDelete();

            $t->string('titulo', 160);
            $t->string('slug', 180)->unique();
            $t->text('descripcion')->nullable();

            $t->enum('operacion', ['venta', 'renta', 'venta_renta']);
            $t->decimal('precio', 14, 2);
            $t->char('moneda', 3)->default('MXN');
            $t->decimal('mantenimiento', 10, 2)->nullable();

            $t->enum('estado', ['borrador','disponible','apartada','vendida','rentada'])->default('borrador');
            $t->boolean('publicada')->default(false);
            $t->boolean('destacada')->default(false);

            $t->decimal('m2_terreno', 10, 2)->nullable();
            $t->decimal('m2_construccion', 10, 2)->nullable();
            $t->unsignedTinyInteger('recamaras')->nullable();
            $t->decimal('banios', 3, 1)->nullable();
            $t->unsignedTinyInteger('niveles')->nullable();
            $t->unsignedTinyInteger('estacionamientos')->nullable();
            $t->unsignedSmallInteger('antiguedad')->nullable();

            $t->string('calle', 160)->nullable();
            $t->string('colonia', 120)->nullable();
            $t->string('ciudad', 120)->default('Manzanillo');
            $t->string('estado_mx', 120)->default('Colima');
            $t->char('cp', 5)->nullable();
            $t->decimal('lat', 10, 7)->nullable();
            $t->decimal('lng', 10, 7)->nullable();
            $t->boolean('ocultar_direccion')->default(false);

            $t->string('video_url')->nullable();
            $t->string('tour_url')->nullable();
            $t->string('expediente')->nullable();

            $t->unsignedInteger('vistas')->default(0);
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
            $t->softDeletes();

            $t->index(['publicada','estado','operacion']);
            $t->index(['tipo_id','precio']);
            $t->index(['ciudad','colonia']);
            $t->fullText(['titulo','descripcion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};
