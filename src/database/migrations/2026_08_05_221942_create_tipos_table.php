<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos', function (Blueprint $t) {
            $t->id();
            $t->string('nombre', 60);
            $t->string('slug', 60)->unique();
            $t->unsignedSmallInteger('orden')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos');
    }
};
