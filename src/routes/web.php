<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\SitemapController;
use App\Models\Municipio;
use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/sitemap.xml", [SitemapController::class, "index"])->name("sitemap");

Route::get("/robots.txt", function () {
    return response("User-agent: *\nDisallow: /admin\n\nSitemap: " . url("/sitemap.xml") . "\n")
        ->header("Content-Type", "text/plain");
});

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

    // Resolved once here (not just filtered via whereHas) so the breadcrumb
    // can show the type's actual name, e.g. "Casas", not just echo the slug back.
    $tipoActual = $request->filled("tipo") ? Tipo::where("slug", $request->tipo)->first() : null;

    if ($request->filled("tipo")) {
        // 0 never matches any id, preserving the old whereHas behaviour of an
        // empty result set for a tipo slug that doesn't exist.
        $query->where("tipo_id", $tipoActual->id ?? 0);
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

    // Canonical only reflects params that actually change the result set, so
    // tracking/junk query strings never create duplicate-content variants.
    $canonicalParams = collect(["tipo", "operacion", "min_price", "max_price", "municipio"])
        ->mapWithKeys(fn ($key) => [$key => $request->query($key)])
        ->filter(fn ($value) => filled($value))
        ->all();

    if ($propiedades->currentPage() > 1) {
        $canonicalParams["page"] = $propiedades->currentPage();
    }

    $canonical = $canonicalParams === []
        ? url("/propiedades")
        : url("/propiedades") . "?" . http_build_query($canonicalParams);

    // Only the properties actually shown on this page of results - matching
    // what's visible, same rule as everywhere else structured data is used here.
    $itemListSchema = [
        "@context" => "https://schema.org",
        "@type" => "ItemList",
        "itemListElement" => $propiedades->values()->map(fn (Propiedad $p, int $i) => [
            "@type" => "ListItem",
            "position" => $i + 1,
            "url" => route("propiedades.show", $p->slug),
            "name" => $p->titulo,
        ])->all(),
    ];

    return view("propiedades.index", compact(
        "propiedades", "navTipos", "navMunicipios", "canonical", "tipoActual", "itemListSchema",
    ));
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
