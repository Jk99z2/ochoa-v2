@extends("layouts.public")

@section("title", $propiedad->titulo . " - " . $siteConfig->nombre_sitio)

@section("meta")
  <link rel="canonical" href="{{ route('propiedades.show', $propiedad->slug) }}">
  <meta name="description" content="{{ Str::limit($propiedad->descripcion ?? $propiedad->titulo, 155) }}">
  <meta property="og:title" content="{{ $propiedad->titulo }} - {{ $siteConfig->nombre_sitio }}">
  <meta property="og:description" content="{{ Str::limit($propiedad->descripcion ?? $propiedad->titulo, 155) }}">
  <meta property="og:type" content="website">
  @if ($propiedad->imagenes->isNotEmpty())
    {{-- The thumbnail, not the original: WhatsApp drops og:images over ~600 KB and shows the site icon instead. --}}
    <meta property="og:image" content="{{ $propiedad->imagenes->first()->thumb_url }}">
  @endif
  @include("partials.real-estate-listing-schema")
@endsection

@push("styles")
  <link rel="stylesheet" href="/css/detail.css">
@endpush

@section("content")
@if (session("success"))
  <div class="alert-success"><div class="wrap">{{ session("success") }}</div></div>
@endif

@include("partials.nav")

@php
  $crumbs = [
    ["name" => "Inicio", "url" => url("/")],
    ["name" => "Propiedades", "url" => route("propiedades.index")],
  ];
  if ($propiedad->tipo) {
    $crumbs[] = ["name" => $propiedad->tipo->nombre, "url" => route("propiedades.index", ["tipo" => $propiedad->tipo->slug])];
  }
  $crumbs[] = ["name" => $propiedad->titulo, "url" => null];
@endphp
<div class="wrap">
  @include("partials.breadcrumbs", ["crumbs" => $crumbs])
</div>

<section class="detail-gallery">
  <div class="wrap">
    <div class="gallery-main">
      @if ($propiedad->imagenes->isNotEmpty())
        <img src="{{ Storage::url($propiedad->imagenes->first()->path) }}" alt="{{ $propiedad->titulo }}" id="gallery-main-img">
      @else
        <div class="gallery-placeholder">Sin imagenes</div>
      @endif
    </div>
    @if ($propiedad->imagenes->count() > 1)
      <div class="gallery-thumbs">
        @foreach ($propiedad->imagenes as $img)
          <img src="{{ $img->thumb_url }}" alt="{{ $img->alt ?? $propiedad->titulo }}" class="gallery-thumb" data-full="{{ Storage::url($img->path) }}" loading="lazy">
        @endforeach
      </div>
    @endif
  </div>
</section>

<section class="detail-body">
  <div class="wrap detail-grid">
    <div class="detail-main">
      <div class="detail-header">
        {{-- Keeps the referring agent so leads from a reshared link still reach them. --}}
        @php $shareUrl = route("propiedades.show", array_filter([$propiedad->slug, "agente" => $referrerAgente?->id])); @endphp
        <div class="detail-top-badges">
          @if ($propiedad->destacada)
            <span class="prop-badge">Destacada</span>
          @endif
          @if ($propiedad->municipio)
            <span class="prop-municipio">{{ $propiedad->municipio->nombre }}</span>
          @endif
          <a href="https://wa.me/?text={{ rawurlencode($propiedad->titulo . " " . $shareUrl) }}" target="_blank" rel="noopener" class="share-btn share-whatsapp" aria-label="Compartir por WhatsApp" title="Compartir por WhatsApp">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.47 14.38c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.64.07-.3-.15-1.25-.46-2.38-1.47-.88-.79-1.47-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.88 1.21 3.08c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.7.63.71.22 1.36.19 1.87.12.57-.09 1.75-.72 2-1.41.25-.69.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35M12.05 21.8h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.44-9.88 9.9-9.88 2.64 0 5.12 1.03 6.99 2.9a9.82 9.82 0 0 1 2.89 6.99c0 5.45-4.44 9.88-9.89 9.88m8.41-18.3A11.82 11.82 0 0 0 12.05 0C5.5 0 .16 5.34.16 11.9c0 2.1.55 4.14 1.59 5.95L.06 24l6.3-1.65a11.88 11.88 0 0 0 5.68 1.45h.01c6.55 0 11.89-5.34 11.89-11.9 0-3.18-1.24-6.16-3.48-8.41"/></svg>
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($shareUrl) }}" target="_blank" rel="noopener" class="share-btn share-facebook" aria-label="Compartir en Facebook" title="Compartir en Facebook">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12.06C22 6.505 17.523 2 12 2S2 6.505 2 12.06c0 5.02 3.657 9.184 8.438 9.94v-7.03H7.898v-2.91h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.242 0-1.63.771-1.63 1.562v1.878h2.773l-.443 2.91h-2.33V22c4.78-.756 8.437-4.92 8.437-9.94Z"/></svg>
          </a>
        </div>
        <h1 class="detail-title">{{ $propiedad->titulo }}</h1>
        @if ($propiedad->clave)
          <div class="detail-meta-row">
            <p class="detail-ref">Ref: {{ $propiedad->clave }}</p>
          </div>
        @endif
        <p class="detail-loc">{{ $propiedad->colonia }}@if($propiedad->colonia && $propiedad->municipio), @endif{{ $propiedad->municipio?->nombre }}, {{ $propiedad->estado_mx }}</p>
        <span class="detail-price">
          ${{ number_format($propiedad->precio, 0) }} {{ $propiedad->moneda }}
          @if ($propiedad->precio_por_m2)<span class="detail-price-period">/ m²</span>@endif
          @if ($propiedad->operacion === "renta")<span class="detail-price-period">/mes</span>@endif
        </span>
        @if ($propiedad->precio_por_m2 && $propiedad->m2_terreno)
          <p class="detail-ref">Total: ${{ number_format($propiedad->precio * $propiedad->m2_terreno, 0) }} {{ $propiedad->moneda }} ({{ $propiedad->m2_terreno }} m²)</p>
        @endif
      </div>

      <div class="detail-stats">
        @if ($propiedad->m2_terreno)<div class="stat"><span class="stat-num">{{ $propiedad->m2_terreno }}</span><span class="stat-label">m2 terreno</span></div>@endif
        @if ($propiedad->m2_construccion)<div class="stat"><span class="stat-num">{{ $propiedad->m2_construccion }}</span><span class="stat-label">m2 construcción</span></div>@endif
        @if ($propiedad->recamaras)<div class="stat"><span class="stat-num">{{ $propiedad->recamaras }}</span><span class="stat-label">Recámaras</span></div>@endif
        @if ($propiedad->banios)<div class="stat"><span class="stat-num">{{ $propiedad->banios }}</span><span class="stat-label">Baños</span></div>@endif
        @if ($propiedad->niveles)<div class="stat"><span class="stat-num">{{ $propiedad->niveles }}</span><span class="stat-label">Niveles</span></div>@endif
        @if ($propiedad->estacionamientos)<div class="stat"><span class="stat-num">{{ $propiedad->estacionamientos }}</span><span class="stat-label">Estacionamientos</span></div>@endif
      </div>

      @if ($propiedad->descripcion)
        <div class="detail-section">
          <h2 class="detail-subtitle">Descripción</h2>
          <p class="detail-desc">{{ $propiedad->descripcion }}</p>
        </div>
      @endif

      @if ($propiedad->amenidades->isNotEmpty())
        <div class="detail-section">
          <h2 class="detail-subtitle">Amenidades</h2>
          <ul class="amenidades-list">
            @foreach ($propiedad->amenidades as $amenidad)
              <li>{{ $amenidad->nombre }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if ($propiedad->tour_url)
        <div class="detail-section">
          <h2 class="detail-subtitle">Tour virtual 360°</h2>
          <iframe
            title="Tour virtual 360°"
            width="100%"
            height="480"
            style="border:0;border-radius:6px;"
            loading="lazy"
            allow="fullscreen; gyroscope; accelerometer; xr-spatial-tracking"
            allowfullscreen
            src="{{ $propiedad->tour_url }}">
          </iframe>
        </div>
      @endif

      @if ($propiedad->lat && $propiedad->lng && !$propiedad->ocultar_direccion)
        <div class="detail-section">
          <h2 class="detail-subtitle">Ubicación</h2>
          <iframe
            width="100%"
            height="360"
            style="border:0;border-radius:6px;"
            loading="lazy"
            allowfullscreen
            src="https://www.google.com/maps?q={{ $propiedad->lat }},{{ $propiedad->lng }}&output=embed">
          </iframe>
        </div>
      @elseif ($propiedad->municipio)
        <div class="detail-section">
          <h2 class="detail-subtitle">Ubicación</h2>
          <p class="detail-desc">{{ $propiedad->colonia }}@if($propiedad->colonia), @endif{{ $propiedad->municipio->nombre }}, {{ $propiedad->estado_mx }}</p>
        </div>
      @endif
    </div>

    <aside class="detail-sidebar">
      <div class="agent-card">
        <h3 class="agent-card-title">Contacta al agente</h3>
        @php $displayAgente = $referrerAgente ?? $propiedad->agente; @endphp
        @if ($displayAgente)
          <div class="agent-info">
            @if ($displayAgente->foto)
              <img src="{{ Storage::url($displayAgente->foto) }}" alt="{{ $displayAgente->nombre }}" class="agent-photo">
            @endif
            <div>
              <p class="agent-name">{{ $displayAgente->nombre }}</p>
              @if ($displayAgente->telefono)<p class="agent-contact">{{ $displayAgente->telefono }}</p>@endif
              @if ($displayAgente->email)<p class="agent-contact">{{ $displayAgente->email }}</p>@endif
            </div>
          </div>
          @if ($displayAgente->whatsapp)
            <a href="https://wa.me/{{ preg_replace("/[^0-9]/", "", $displayAgente->whatsapp) }}" target="_blank" class="agent-cta">Contactar por WhatsApp</a>
          @endif
        @else
          <p class="agent-contact">{{ $siteConfig->telefono_oficina }}</p>
          <p class="agent-contact">{{ $siteConfig->telefono_celular }}</p>
        @endif
        <form method="POST" action="{{ route("leads.store") }}" class="lead-form">
          @csrf
          <input type="text" name="website" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
          <input type="hidden" name="form_time" value="{{ time() }}">
          <input type="hidden" name="propiedad_id" value="{{ $propiedad->id }}">
          <input type="hidden" name="agente_id" value="{{ $referrerAgente?->id }}">
          <input type="text" name="nombre" placeholder="Tu nombre" required>
          <input type="email" name="email" placeholder="Tu email">
          <input type="tel" name="telefono" placeholder="Tu teléfono">
          <textarea name="mensaje" placeholder="Mensaje" rows="3">Me interesa esta propiedad: {{ $propiedad->titulo }}</textarea>
          @error("nombre")<p class="lead-error">{{ $message }}</p>@enderror
          <button type="submit" class="agent-cta">Enviar mensaje</button>
        </form>
      </div>
    </aside>
  </div>
</section>

@include("partials.footer")

@include("partials.nav-script")

<script>
(function() {
  var mainImg = document.getElementById("gallery-main-img");
  var thumbs = document.querySelectorAll(".gallery-thumb");
  if (!mainImg || thumbs.length === 0) return;
  thumbs.forEach(function(thumb, i) {
    if (i === 0) thumb.classList.add("active");
    thumb.addEventListener("click", function() {
      mainImg.src = this.dataset.full;
      thumbs.forEach(function(t) { t.classList.remove("active"); });
      this.classList.add("active");
    });
  });
})();
</script>
@endsection
