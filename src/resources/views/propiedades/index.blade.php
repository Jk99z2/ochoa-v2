@extends("layouts.public")

@section("title", "Propiedades en Manzanillo, Colima - " . $siteConfig->nombre_sitio)

@section("meta")
  <meta name="description" content="Explora casas, terrenos y departamentos en venta y renta en Manzanillo, Colima. Encuentra tu proxima propiedad con {{ $siteConfig->nombre_sitio }}.">
@endsection

@push("styles")
  <link rel="stylesheet" href="/css/listing.css">
@endpush

@section("content")
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
            <x-prop-card :propiedad="$p" />
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
@endsection
