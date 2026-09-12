<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $siteConfig->nombre_sitio }} - Manzanillo, Colima</title>
  <meta name="description" content="Encuentra casas, terrenos y departamentos en venta y renta en Manzanillo, Colima. Ochoa Real Estate Services, tu inmobiliaria de confianza.">
  <meta property="og:title" content="Ochoa Real Estate Services - Manzanillo, Colima">
  <meta property="og:description" content="Encuentra tu proxima propiedad en Manzanillo, Colima.">
  <meta property="og:type" content="website">
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
  <link rel="stylesheet" href="/css/home.css">
</head>
<body>
<div id="loader"><div class="loader-ring"></div></div>

@if (session("success"))
  <div class="alert-success"><div class="wrap">{{ session("success") }}</div></div>
@endif

@include("partials.nav")

<section class="hero" id="hero">
  @foreach ($destacadas as $i => $p)
    <div class="hero-slide @if($i === 0) active @endif" data-idx="{{ $i }}">
      @if ($p->imagenes->isNotEmpty())
        <img src="{{ Storage::url($p->imagenes->first()->path) }}" alt="{{ $p->titulo }}">
      @endif
    </div>
  @endforeach

  <div class="hero-content">
    <div class="wrap">
      @foreach ($destacadas as $i => $p)
        <div class="hero-text @if($i === 0) active @endif" data-idx="{{ $i }}">
          <div class="hero-tag">{{ $p->municipio?->nombre }}, {{ $p->estado_mx }}</div>
          <h1 class="hero-title">{{ $p->titulo }}</h1>
          <p class="hero-desc">{{ $p->descripcion }}</p>
          <span class="hero-price">${{ number_format($p->precio, 0) }} {{ $p->moneda }}</span>
          <a href="{{ route("propiedades.show", $p->slug) }}" class="hero-cta">
            Ver propiedad <span class="hero-cta-arrow"></span>
          </a>
        </div>
      @endforeach
    </div>
  </div>

  <div class="hero-dots">
    @foreach ($destacadas as $i => $p)
      <button class="hero-dot @if($i === 0) active @endif" data-idx="{{ $i }}"></button>
    @endforeach
  </div>

  <div class="hero-loc" id="hero-loc">
    <span id="hero-loc-text">{{ $destacadas->first()->municipio?->nombre ?? "Manzanillo" }}, Colima</span>
  </div>
</section>
<section class="hero-search-wrap">
  <div class="wrap">
    <form method="GET" action="{{ route("propiedades.index") }}" class="hero-search">
      <div class="hs-field">
        <label>Tipo</label>
        <select name="tipo">
          <option value="">Todos</option>
          @foreach ($navTipos as $navTipo)
            <option value="{{ $navTipo->slug }}">{{ $navTipo->nombre }}</option>
          @endforeach
        </select>
      </div>
      <div class="hs-field">
        <label>Operacion</label>
        <select name="operacion">
          <option value="">Todas</option>
          <option value="venta">Venta</option>
          <option value="renta">Renta</option>
        </select>
      </div>
      <div class="hs-field">
        <label>Municipio</label>
        <select name="municipio">
          <option value="">Todos</option>
          @foreach ($navMunicipios as $navMunicipio)
            <option value="{{ $navMunicipio->clave }}">{{ $navMunicipio->nombre }}</option>
          @endforeach
        </select>
      </div>
      <div class="hs-field">
        <label>Precio maximo</label>
        <input type="number" name="max_price" placeholder="Sin limite">
      </div>
      <button type="submit" class="hs-submit">
        <svg class="hs-submit-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        Buscar
      </button>
    </form>
  </div>
</section>

<section class="section" style="padding-bottom:0;">
  <div class="wrap">
    <div class="features">
      <div class="feature">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-7 9 7"/><path d="M5 10v9a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1v-9"/></svg>
        </div>
        <h3>Encuentra el lugar ideal</h3>
        <p>Gran variedad de inmuebles en Colima y sus alrededores para cada necesidad.</p>
      </div>
      <div class="feature">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3"/><path d="M3 20c0-3 2.5-5 6-5s6 2 6 5"/><circle cx="17" cy="9" r="2.4"/><path d="M15.5 15.3c2.6.3 4.5 1.9 4.5 4.2"/></svg>
        </div>
        <h3>Agentes con experiencia</h3>
        <p>Especialistas listos para orientarte en cada paso del proceso.</p>
      </div>
      <div class="feature">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h13M17 8l-3-3M17 8l-3 3"/><path d="M20 16H7M7 16l3-3M7 16l3 3"/></svg>
        </div>
        <h3>Compra y renta</h3>
        <p>Catalogo amplio de propiedades en venta y renta a tu medida.</p>
      </div>
      <div class="feature">
        <div class="feature-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/><path d="M9 12l2 2 4-4"/></svg>
        </div>
        <h3>Cuida tu inversion</h3>
        <p>Manzanillo crece constantemente. Invertir aqui es siempre un acierto.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" id="nuevas">
  <div class="wrap">
    <div class="slider-head">
      <div>
        <div class="section-eyebrow">Publicaciones recientes</div>
        <h2 class="section-title">Nuevas <em>propiedades</em></h2>
        <p class="section-desc">Las incorporaciones mas recientes a nuestro catalogo, listas para visitar.</p>
      </div>
      <div class="slider-nav">
        <button class="slider-btn" id="sl-prev" aria-label="Anterior" type="button">&#8592;</button>
        <button class="slider-btn" id="sl-next" aria-label="Siguiente" type="button">&#8594;</button>
      </div>
    </div>
    <div class="slider-outer">
      <div class="slider-track" id="slider-track">
        @foreach ($nuevas as $p)
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
    </div>
    <div class="slider-dots" id="slider-dots"></div>
  </div>
</section>

<section class="section section-alt" id="recomendadas">
  <div class="wrap">
    <div class="slider-head">
      <div>
        <div class="section-eyebrow">Seleccion especial</div>
        <h2 class="section-title">Propiedades <em>recomendadas</em></h2>
        <p class="section-desc">Una curaduria de las mejores opciones disponibles ahora mismo en Manzanillo.</p>
      </div>
      <div class="slider-nav">
        <button class="slider-btn" id="rec-prev" aria-label="Anterior" type="button">&#8592;</button>
        <button class="slider-btn" id="rec-next" aria-label="Siguiente" type="button">&#8594;</button>
      </div>
    </div>
    <div class="slider-outer">
      <div class="slider-track" id="rec-track">
        @foreach ($recomendadas as $p)
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
    </div>
    <div class="slider-dots" id="rec-dots"></div>
  </div>
</section>
<section class="contact-section" id="contacto-form">
  <div class="wrap contact-grid">
    <div class="contact-col-form">
      <div class="section-eyebrow">Contacto</div>
      <h2 class="section-title">Preguntanos por <em>otras propiedades</em></h2>
      <p class="contact-lead">Cuentanos que buscas y un agente te contactara pronto.</p>
      <form method="POST" action="{{ route("leads.store") }}" class="contact-form">
        @csrf
        <input type="text" name="website" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
        <input type="hidden" name="form_time" value="{{ time() }}">
        <div class="cf-row">
          <input type="text" name="nombre" placeholder="Tu nombre" required>
          <input type="email" name="email" placeholder="Tu email">
        </div>
        <div class="cf-row">
          <input type="tel" name="telefono" placeholder="Tu telefono">
        </div>
        <textarea name="mensaje" placeholder="Que tipo de propiedad buscas?" rows="4"></textarea>
        @error("nombre")<p class="lead-error">{{ $message }}</p>@enderror
        <button type="submit" class="hs-submit">Enviar mensaje</button>
      </form>
    </div>
    <div class="contact-col-map">
      <iframe src="{{ $siteConfig->mapa_embed_url }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>
  </div>
</section>
@include("partials.footer")

@include("partials.nav-script")

<script>
(function() {
  var loader = document.getElementById("loader");
  if (!loader) return;
  window.addEventListener("load", function() {
    loader.classList.add("gone");
  });
  setTimeout(function() { loader.classList.add("gone"); }, 1500);
})();

(function() {
  var slides = document.querySelectorAll(".hero-slide");
  var texts = document.querySelectorAll(".hero-text");
  var dots = document.querySelectorAll(".hero-dot");
  var total = slides.length;
  var current = 0;
  var timer = null;
  if (total < 2) return;

  function goTo(idx) {
    slides[current].classList.remove("active");
    texts[current].classList.remove("active");
    if (dots[current]) dots[current].classList.remove("active");
    current = ((idx % total) + total) % total;
    slides[current].classList.add("active");
    texts[current].classList.add("active");
    if (dots[current]) dots[current].classList.add("active");
  }
  function startTimer() {
    if (timer) clearInterval(timer);
    timer = setInterval(function() { goTo(current + 1); }, 5500);
  }
  dots.forEach(function(d) {
    d.addEventListener("click", function() {
      goTo(parseInt(this.dataset.idx));
      startTimer();
    });
  });
  startTimer();
})();

function initSlider(trackId, prevId, nextId, dotsId) {
  var track = document.getElementById(trackId);
  var btnP = document.getElementById(prevId);
  var btnN = document.getElementById(nextId);
  var dotsWrap = document.getElementById(dotsId);
  if (!track) return;
  var page = 0;
  var gap = 24;

  function visCount() {
    if (window.innerWidth < 540) return 1;
    if (window.innerWidth < 860) return 2;
    return 3;
  }
  function pageCount() {
    return Math.max(1, Math.ceil(track.children.length / visCount()));
  }
  function maxPos() {
    return Math.max(0, track.children.length - visCount());
  }
  function cardStep() {
    var count = visCount();
    var outerWidth = track.parentElement.offsetWidth;
    var cardWidth = (outerWidth - gap * (count - 1)) / count;
    return cardWidth + gap;
  }
  function posForPage(p) {
    return Math.max(0, Math.min(p * visCount(), maxPos()));
  }
  function updateButtons() {
    if (!btnP || !btnN) return;
    btnP.disabled = page <= 0;
    btnN.disabled = page >= pageCount() - 1;
  }
  function updateDots() {
    if (!dotsWrap) return;
    dotsWrap.querySelectorAll(".slider-dot").forEach(function(dot, i) {
      dot.classList.toggle("active", i === page);
    });
  }
  function buildDots() {
    if (!dotsWrap) return;
    var pages = pageCount();
    if (pages <= 1) {
      dotsWrap.innerHTML = "";
      return;
    }
    var html = "";
    for (var i = 0; i < pages; i++) {
      html += '<button class="slider-dot" data-page="' + i + '" aria-label="Pagina ' + (i + 1) + '" type="button"></button>';
    }
    dotsWrap.innerHTML = html;
    dotsWrap.querySelectorAll(".slider-dot").forEach(function(dot) {
      dot.addEventListener("click", function() {
        goTo(parseInt(this.dataset.page, 10));
      });
    });
  }
  function render() {
    track.style.transform = "translateX(-" + (posForPage(page) * cardStep()) + "px)";
    updateButtons();
    updateDots();
  }
  function goTo(p) {
    page = Math.max(0, Math.min(p, pageCount() - 1));
    render();
  }
  function move(dir) { goTo(page + dir); }

  if (btnP) btnP.addEventListener("click", function() { move(-1); });
  if (btnN) btnN.addEventListener("click", function() { move(1); });
  window.addEventListener("resize", function() {
    buildDots();
    goTo(page);
  });
  buildDots();
  render();
}

initSlider("slider-track", "sl-prev", "sl-next", "slider-dots");
initSlider("rec-track", "rec-prev", "rec-next", "rec-dots");
</script>

</body>
</html>
