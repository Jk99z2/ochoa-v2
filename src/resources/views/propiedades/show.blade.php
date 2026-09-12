<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $propiedad->titulo }} - {{ $siteConfig->nombre_sitio }}</title>
  <meta name="description" content="{{ Str::limit($propiedad->descripcion ?? $propiedad->titulo, 155) }}">
  <meta property="og:title" content="{{ $propiedad->titulo }} - {{ $siteConfig->nombre_sitio }}">
  <meta property="og:description" content="{{ Str::limit($propiedad->descripcion ?? $propiedad->titulo, 155) }}">
  <meta property="og:type" content="website">
  @if ($propiedad->imagenes->isNotEmpty())
  <meta property="og:image" content="{{ Storage::url($propiedad->imagenes->first()->path) }}">
  @endif
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="/css/animate.css">
  <link rel="stylesheet" href="/css/owl.carousel.min.css">
  <link rel="stylesheet" href="/css/owl.theme.default.min.css">
  <link rel="stylesheet" href="/css/magnific-popup.css">
  <link rel="stylesheet" href="/css/aos.css">
  <link rel="stylesheet" href="/css/ionicons.min.css">
  <link rel="stylesheet" href="/css/flaticon.css">
  <link rel="stylesheet" href="/css/icomoon.css">
  <link rel="stylesheet" href="/css/style.css">
  <link rel="icon" href="{{ $siteConfig->favicon_path ? Storage::url($siteConfig->favicon_path) : '/logos/logochoa.png' }}" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/site.css">
  <link rel="stylesheet" href="/css/detail.css">
</head>
<body>
@if (session("success"))
  <div class="alert-success"><div class="wrap">{{ session("success") }}</div></div>
@endif

@include("partials.nav")

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
          <img src="{{ Storage::url($img->path) }}" alt="{{ $img->alt ?? $propiedad->titulo }}" class="gallery-thumb" data-full="{{ Storage::url($img->path) }}">
        @endforeach
      </div>
    @endif
  </div>
</section>

<section class="detail-body">
  <div class="wrap detail-grid">
    <div class="detail-main">
      <div class="detail-header">
        @if ($propiedad->destacada || $propiedad->municipio)
          <div class="detail-top-badges">
            @if ($propiedad->destacada)
              <span class="prop-badge">Destacada</span>
            @endif
            @if ($propiedad->municipio)
              <span class="prop-municipio">{{ $propiedad->municipio->nombre }}</span>
            @endif
          </div>
        @endif
        <h1 class="detail-title">{{ $propiedad->titulo }}</h1>
        @if ($propiedad->clave)
          <div class="detail-meta-row">
            <p class="detail-ref">Ref: {{ $propiedad->clave }}</p>
          </div>
        @endif
        <p class="detail-loc">{{ $propiedad->colonia }}@if($propiedad->colonia && $propiedad->municipio), @endif{{ $propiedad->municipio?->nombre }}, {{ $propiedad->estado_mx }}</p>
        <span class="detail-price">
          ${{ number_format($propiedad->precio, 0) }} {{ $propiedad->moneda }}
          @if ($propiedad->operacion === "renta")<span class="detail-price-period">/mes</span>@endif
        </span>
      </div>

      <div class="detail-stats">
        @if ($propiedad->m2_terreno)<div class="stat"><span class="stat-num">{{ $propiedad->m2_terreno }}</span><span class="stat-label">m2 terreno</span></div>@endif
        @if ($propiedad->m2_construccion)<div class="stat"><span class="stat-num">{{ $propiedad->m2_construccion }}</span><span class="stat-label">m2 construccion</span></div>@endif
        @if ($propiedad->recamaras)<div class="stat"><span class="stat-num">{{ $propiedad->recamaras }}</span><span class="stat-label">Recamaras</span></div>@endif
        @if ($propiedad->banios)<div class="stat"><span class="stat-num">{{ $propiedad->banios }}</span><span class="stat-label">Banos</span></div>@endif
        @if ($propiedad->niveles)<div class="stat"><span class="stat-num">{{ $propiedad->niveles }}</span><span class="stat-label">Niveles</span></div>@endif
        @if ($propiedad->estacionamientos)<div class="stat"><span class="stat-num">{{ $propiedad->estacionamientos }}</span><span class="stat-label">Estacionamientos</span></div>@endif
      </div>

      @if ($propiedad->descripcion)
        <div class="detail-section">
          <h2 class="detail-subtitle">Descripcion</h2>
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

      @if ($propiedad->lat && $propiedad->lng && !$propiedad->ocultar_direccion)
        <div class="detail-section">
          <h2 class="detail-subtitle">Ubicacion</h2>
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
          <h2 class="detail-subtitle">Ubicacion</h2>
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
          <input type="tel" name="telefono" placeholder="Tu telefono">
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

</body>
</html>
