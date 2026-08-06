<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagenes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('propiedad_id')->constrained('propiedades')->cascadeOnDelete();
            $t->string('path');
            $t->string('alt', 160)->nullable();
            $t->unsignedSmallInteger('orden')->default(0);
            $t->boolean('principal')->default(false);
            $t->timestamps();
            $t->index(['propiedad_id','orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imagenes');
    }
};
