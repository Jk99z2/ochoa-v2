<?php

use App\Http\Controllers\LeadController;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    $navTipos = Tipo::orderBy("orden")->get();
    $navMunicipios = Municipio::orderBy("orden")->get();

    $destacadas = Propiedad::where("publicada", true)
        ->where("destacada", true)
        ->with(["tipo", "municipio", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->latest()
        ->take(5)
        ->get();

    if ($destacadas->isEmpty()) {
        $destacadas = Propiedad::where("publicada", true)
            ->with(["tipo", "municipio", "imagenes" => function ($query) {
                $query->orderByDesc("principal")->orderBy("orden");
            }])
            ->latest()
            ->take(5)
            ->get();
    }

    $nuevas = Propiedad::where("publicada", true)
        ->with(["tipo", "municipio", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->latest()
        ->take(12)
        ->get();

    $recomendadas = Propiedad::where("publicada", true)
        ->where("destacada", true)
        ->with(["tipo", "municipio", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->latest()
        ->take(12)
        ->get();

    return view("welcome", compact("destacadas", "nuevas", "recomendadas", "navTipos", "navMunicipios"));
});

Route::get("/propiedades", function (Request $request) {
    $navTipos = Tipo::orderBy("orden")->get();
    $navMunicipios = Municipio::orderBy("orden")->get();

    $query = Propiedad::where("publicada", true)
        ->with(["tipo", "municipio", "imagenes" => function ($q) {
            $q->orderByDesc("principal")->orderBy("orden");
        }]);

    if ($request->filled("tipo")) {
        $query->whereHas("tipo", function ($q) use ($request) {
            $q->where("slug", $request->tipo);
        });
    }

    if ($request->filled("operacion")) {
        $query->where("operacion", $request->operacion);
    }

    if ($request->filled("min_price")) {
        $query->where("precio", ">=", $request->min_price);
    }

    if ($request->filled("max_price")) {
        $query->where("precio", "<=", $request->max_price);
    }

    if ($request->filled("municipio")) {
        $query->whereHas("municipio", function ($q) use ($request) {
            $q->where("clave", $request->municipio);
        });
    }

    $propiedades = $query->latest()->paginate(9)->withQueryString();

    return view("propiedades.index", compact("propiedades", "navTipos", "navMunicipios"));
})->name("propiedades.index");

Route::get("/propiedades/{slug}", function (string $slug, \Illuminate\Http\Request $request) {
    $navTipos = Tipo::orderBy("orden")->get();

    $propiedad = Propiedad::where("slug", $slug)
        ->where("publicada", true)
        ->with(["agente", "tipo", "municipio", "amenidades", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->firstOrFail();

    $viewedKey = "viewed_propiedad_" . $propiedad->id;
    if (! $request->session()->has($viewedKey)) {
        $propiedad->increment("vistas");
        $request->session()->put($viewedKey, true);
    }

    $referrerAgente = null;
    if ($request->filled("agente")) {
        $referrerAgente = \App\Models\Agente::find($request->input("agente"));
    }

    return view("propiedades.show", compact("propiedad", "navTipos", "referrerAgente"));
})->name("propiedades.show");

Route::post("/leads", [LeadController::class, "store"])->name("leads.store")->middleware("throttle:5,1");
