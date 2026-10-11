<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("configuracion", function (Blueprint $table) {
            // Office coordinates, for the LocalBusiness/RealEstateAgent JSON-LD geo field.
            // Same precision as propiedades.lat/lng.
            $table->decimal("lat", 10, 7)->nullable()->after("mapa_embed_url");
            $table->decimal("lng", 10, 7)->nullable()->after("lat");
        });
    }

    public function down(): void
    {
        Schema::table("configuracion", function (Blueprint $table) {
            $table->dropColumn(["lat", "lng"]);
        });
    }
};
