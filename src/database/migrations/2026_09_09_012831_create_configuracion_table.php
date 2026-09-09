<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("configuracion", function (Blueprint $table) {
            $table->id();

            // Identidad
            $table->string("nombre_sitio")->default("Ochoa Real Estate Services");
            $table->string("logo_path")->nullable();
            $table->string("favicon_path")->nullable();

            // Contacto - encabezado
            $table->string("telefono_oficina")->nullable();
            $table->string("telefono_celular")->nullable();

            // Contacto - pie de pagina
            $table->string("direccion")->nullable();
            $table->string("email_contacto")->nullable();
            $table->string("horario")->nullable();
            $table->string("facebook_url")->nullable();
            $table->string("instagram_url")->nullable();
            $table->string("whatsapp_numero")->nullable();

            // Landing / hero
            $table->string("hero_titulo")->nullable();
            $table->text("hero_subtitulo")->nullable();

            // Mapa de contacto
            $table->text("mapa_embed_url")->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("configuracion");
    }
};
