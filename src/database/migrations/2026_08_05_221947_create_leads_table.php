<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $t) {
            $t->id();
            $t->foreignId('propiedad_id')->nullable()->constrained('propiedades')->nullOnDelete();
            $t->string('nombre', 120);
            $t->string('email', 120)->nullable();
            $t->string('telefono', 30)->nullable();
            $t->text('mensaje')->nullable();
            $t->enum('origen', ['formulario','whatsapp','llamada','otro'])->default('formulario');
            $t->enum('estatus', ['nuevo','contactado','en_proceso','cerrado','perdido'])->default('nuevo');
            $t->timestamps();
            $t->index(['estatus','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
