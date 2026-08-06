<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agentes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('nombre', 120);
            $t->string('email', 120)->unique();
            $t->string('telefono', 30)->nullable();
            $t->string('whatsapp', 30)->nullable();
            $t->string('foto')->nullable();
            $t->boolean('activo')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agentes');
    }
};
