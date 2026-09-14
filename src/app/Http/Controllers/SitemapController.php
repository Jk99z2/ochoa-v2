<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $propiedades = Propiedad::where("publicada", true)
            ->orderByDesc("updated_at")
            ->get(["slug", "updated_at"]);

        $urls = collect([
            ["loc" => url("/"), "lastmod" => now()->toAtomString(), "changefreq" => "daily", "priority" => "1.0"],
            ["loc" => url("/propiedades"), "lastmod" => now()->toAtomString(), "changefreq" => "daily", "priority" => "0.9"],
        ])->concat($propiedades->map(fn (Propiedad $p) => [
            "loc" => url("/propiedades/{$p->slug}"),
            "lastmod" => $p->updated_at->toAtomString(),
            "changefreq" => "weekly",
            "priority" => "0.8",
        ]));

        return response(view("sitemap", compact("urls")))
            ->header("Content-Type", "text/xml");
    }
}
