<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Propiedades en Manzanillo, Colima - {{ $siteConfig->nombre_sitio }}</title>
  <meta name="description" content="Explora casas, terrenos y departamentos en venta y renta en Manzanillo, Colima. Encuentra tu proxima propiedad con {{ $siteConfig->nombre_sitio }}.">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="/css/animate.css">
  <link rel="stylesheet" href="/css/ionicons.min.css">
  <link rel="stylesheet" href="/css/flaticon.css">
  <link rel="stylesheet" href="/css/icomoon.css">
  <link rel="stylesheet" href="/css/style.css">
  <link rel="icon" href="{{ $siteConfig->favicon_path ? Storage::url($siteConfig->favicon_path) : '/logos/logochoa.png' }}" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/site.css">
  <link rel="stylesheet" href="/css/listing.css">
</head>
<body>

@include("partials.nav")

<section class="listing-hero">
  <div class="wrap">
    <h1 class="listing-title">Propiedades</h1>
    <p class="listing-count">{{ $propiedades->total() }} {{ $propiedades->total() === 1 ? "propiedad encontrada" : "propiedades encontradas" }}</p>
  </div>
</section>

<section class="listing-body">
  <div class="wrap listing-grid">
    <aside class="filters">
      <form method="GET" action="{{ route("propiedades.index") }}">
        <h3 class="filters-title">Filtrar</h3>

        <div class="filter-group">
          <label>Tipo</label>
          <select name="tipo">
            <option value="">Todos</option>
            @foreach ($navTipos as $navTipo)
              <option value="{{ $navTipo->slug }}" @selected(request("tipo") === $navTipo->slug)>{{ $navTipo->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="filter-group">
          <label>Operacion</label>
          <select name="operacion">
            <option value="">Todas</option>
            <option value="venta" @selected(request("operacion") === "venta")>Venta</option>
            <option value="renta" @selected(request("operacion") === "renta")>Renta</option>
            <option value="venta_renta" @selected(request("operacion") === "venta_renta")>Venta y renta</option>
          </select>
        </div>

        <div class="filter-group">
          <label>Municipio</label>
          <select name="municipio">
            <option value="">Todos</option>
            @foreach ($navMunicipios as $navMunicipio)
              <option value="{{ $navMunicipio->clave }}" @selected(request("municipio") === $navMunicipio->clave)>{{ $navMunicipio->nombre }}</option>
            @endforeach
          </select>
        </div>

        <div class="filter-group">
          <label>Precio minimo</label>
          <input type="number" name="min_price" value="{{ request("min_price") }}" placeholder="0">
        </div>

        <div class="filter-group">
          <label>Precio maximo</label>
          <input type="number" name="max_price" value="{{ request("max_price") }}" placeholder="Sin limite">
        </div>

        <button type="submit" class="filter-submit">Aplicar filtros</button>
        @if (request()->anyFilled(["tipo", "operacion", "municipio", "min_price", "max_price"]))
          <a href="{{ route("propiedades.index") }}" class="filter-clear">Limpiar filtros</a>
        @endif
      </form>
    </aside>

    <div class="listing-results">
      @if ($propiedades->isEmpty())
        <div class="no-results">
          <p>No se encontraron propiedades con esos filtros.</p>
          <a href="{{ route("propiedades.index") }}" class="filter-clear">Ver todas las propiedades</a>
        </div>
      @else
        <div class="results-grid">
          @foreach ($propiedades as $p)
            <div class="prop-card">
              <div class="prop-img">
                <a href="{{ route("propiedades.show", $p->slug) }}">
                  @if ($p->imagenes->isNotEmpty())
                    <img src="{{ Storage::url($p->imagenes->first()->path) }}" alt="{{ $p->titulo }}" loading="lazy">
                  @endif
                </a>
                <span class="prop-badge">
                  @if ($p->operacion === "venta") En venta
                  @elseif ($p->operacion === "renta") En renta
                  @else Venta y renta
                  @endif
                </span>
                @if ($p->municipio)
                  <span class="prop-municipio">{{ $p->municipio->nombre }}</span>
                @endif
              </div>
              <div class="prop-body">
                <h3><a href="{{ route("propiedades.show", $p->slug) }}">{{ $p->titulo }}</a></h3>
                <p class="prop-cat">{{ $p->tipo?->nombre }}</p>
                <span class="prop-price">${{ number_format($p->precio, 0) }} {{ $p->moneda }}</span>
                <div class="prop-meta">
                  @if ($p->m2_construccion)<span>{{ $p->m2_construccion }} m2</span>@endif
                  @if ($p->banios)<span>{{ $p->banios }} baños</span>@endif
                  @if ($p->recamaras)<span>{{ $p->recamaras }} rec</span>@endif
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="listing-pagination">
          {{ $propiedades->links() }}
        </div>
      @endif
    </div>
  </div>
</section>

@include("partials.footer")

@include("partials.nav-script")

</body>
</html>
