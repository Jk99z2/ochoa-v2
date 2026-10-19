@props(["propiedad"])

<div class="prop-card">
  <div class="prop-img">
    <a href="{{ route("propiedades.show", $propiedad->slug) }}">
      @if ($propiedad->imagenes->isNotEmpty())
        <img src="{{ $propiedad->imagenes->first()->thumb_url }}" alt="{{ $propiedad->titulo }}" loading="lazy">
      @endif
    </a>
    <span class="prop-badge">
      @if ($propiedad->operacion === "venta") En venta
      @elseif ($propiedad->operacion === "renta") En renta
      @else Venta y renta
      @endif
    </span>
    @if ($propiedad->municipio)
      <span class="prop-municipio">{{ $propiedad->municipio->nombre }}</span>
    @endif
  </div>
  <div class="prop-body">
    <h3><a href="{{ route("propiedades.show", $propiedad->slug) }}">{{ $propiedad->titulo }}</a></h3>
    <p class="prop-cat">{{ $propiedad->tipo?->nombre }}</p>
    <span class="prop-price">${{ number_format($propiedad->precio, 0) }} {{ $propiedad->moneda }}@if ($propiedad->precio_por_m2) por m²@endif</span>
    <div class="prop-meta">
      @if ($propiedad->m2_terreno > 0)<span>{{ Number::format($propiedad->m2_terreno, maxPrecision: 2) }} m² terreno</span>@endif
      @if ($propiedad->m2_construccion > 0)<span>{{ Number::format($propiedad->m2_construccion, maxPrecision: 2) }} m² const.</span>@endif
      @if ($propiedad->banios > 0)<span>{{ Number::format($propiedad->banios, maxPrecision: 1) }} baños</span>@endif
      @if ($propiedad->recamaras)<span>{{ $propiedad->recamaras }} rec</span>@endif
    </div>
  </div>
</div>
