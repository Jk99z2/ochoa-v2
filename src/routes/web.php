<?php

use App\Models\Propiedad;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    $destacadas = Propiedad::where("publicada", true)
        ->where("destacada", true)
        ->with(["tipo", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->latest()
        ->take(5)
        ->get();

    if ($destacadas->isEmpty()) {
        $destacadas = Propiedad::where("publicada", true)
            ->with(["tipo", "imagenes" => function ($query) {
                $query->orderByDesc("principal")->orderBy("orden");
            }])
            ->latest()
            ->take(5)
            ->get();
    }

    $nuevas = Propiedad::where("publicada", true)
        ->with(["tipo", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->latest()
        ->take(6)
        ->get();

    $recomendadas = Propiedad::where("publicada", true)
        ->where("destacada", true)
        ->with(["tipo", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->latest()
        ->take(4)
        ->get();

    return view("welcome", compact("destacadas", "nuevas", "recomendadas"));
});

Route::get("/propiedades/{slug}", function (string $slug) {
    $propiedad = Propiedad::where("slug", $slug)
        ->where("publicada", true)
        ->with(["agente", "tipo", "amenidades", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->firstOrFail();

    return view("propiedades.show", compact("propiedad"));
})->name("propiedades.show");
