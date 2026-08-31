<?php

use App\Models\Propiedad;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    $navTipos = Tipo::orderBy("orden")->get();

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

    return view("welcome", compact("destacadas", "nuevas", "recomendadas", "navTipos"));
});

Route::get("/propiedades", function (Request $request) {
    $navTipos = Tipo::orderBy("orden")->get();

    $query = Propiedad::where("publicada", true)
        ->with(["tipo", "imagenes" => function ($q) {
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

    if ($request->filled("ciudad")) {
        $query->where("ciudad", "like", "%" . $request->ciudad . "%");
    }

    $propiedades = $query->latest()->paginate(9)->withQueryString();

    return view("propiedades.index", compact("propiedades", "navTipos"));
})->name("propiedades.index");

Route::get("/propiedades/{slug}", function (string $slug) {
    $navTipos = Tipo::orderBy("orden")->get();

    $propiedad = Propiedad::where("slug", $slug)
        ->where("publicada", true)
        ->with(["agente", "tipo", "amenidades", "imagenes" => function ($query) {
            $query->orderByDesc("principal")->orderBy("orden");
        }])
        ->firstOrFail();

    return view("propiedades.show", compact("propiedad", "navTipos"));
})->name("propiedades.show");

Route::post("/leads", function (Request $request) {
    if (!empty($request->input("website"))) {
        return back()->with("success", "Gracias por tu mensaje. Nos pondremos en contacto contigo pronto.");
    }

    $formTime = (int) $request->input("form_time", 0);
    if ($formTime > 0 && (time() - $formTime) < 3) {
        return back()->withErrors(["nombre" => "Por favor intenta de nuevo."])->withInput();
    }

    $validated = $request->validate([
        "nombre" => "required|string|max:120",
        "email" => "nullable|email|max:120",
        "telefono" => "nullable|string|max:30",
        "mensaje" => "nullable|string",
        "propiedad_id" => "nullable|exists:propiedades,id",
    ]);

    \App\Models\Lead::create([
        "propiedad_id" => $validated["propiedad_id"] ?? null,
        "nombre" => $validated["nombre"],
        "email" => $validated["email"] ?? null,
        "telefono" => $validated["telefono"] ?? null,
        "mensaje" => $validated["mensaje"] ?? null,
        "origen" => "formulario",
        "estatus" => "nuevo",
    ]);

    return back()->with("success", "Gracias por tu mensaje. Nos pondremos en contacto contigo pronto.");
})->name("leads.store")->middleware("throttle:5,1");
