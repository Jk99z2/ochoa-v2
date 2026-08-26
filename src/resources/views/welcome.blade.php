<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ochoa Real Estate Services - Manzanillo, Colima</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
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
  <link rel="icon" href="/logos/logochoa.png" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
</head>
<body>

<div class="topbar">
  <div class="wrap">
    <a href="https://www.facebook.com/ochoainmobiliaria/" target="_blank"><span class="icon-facebook"></span> Facebook</a>
    <div class="phones">
      <span>+52 (314) 333-3202</span>
      <span>+52 (314) 376-9162</span>
    </div>
  </div>
</div>

<nav class="nav">
  <div class="wrap">
    <a href="/" class="nav-brand">
      <img src="/logos/logochoa.png" alt="Ochoa Real Estate">
      <div class="nav-brand-text">Ochoa Real <em>Estate</em></div>
    </a>
    <button class="nav-burger" id="burger">Menu</button>
    <ul class="nav-menu" id="nav-menu">
      <li><a href="/" class="active">Inicio</a></li>
      <li><a href="#nuevas">Propiedades</a></li>
      <li><a href="#contacto">Contacto</a></li>
    </ul>
  </div>
</nav>

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
          <div class="hero-tag">{{ $p->ciudad }}, {{ $p->estado_mx }}</div>
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
    <span id="hero-loc-text">{{ $destacadas->first()->ciudad ?? "Manzanillo" }}, Colima</span>
  </div>
</section>

<style>
:root { --ink: #111010; --gold: #b8872a; --gold-lt: #d4a84b; --cream: #f5f1ea; --warm: #ede8de; --muted: #7a7468; --white: #ffffff; --border: rgba(184,135,42,.18); --ease: cubic-bezier(.4,0,.2,1); }
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }
body { font-family: "DM Sans", sans-serif; background: var(--white); color: var(--ink); overflow-x: hidden; }
a { text-decoration: none; color: inherit; }
img { display: block; max-width: 100%; }
ul { list-style: none; }
.topbar { background: var(--ink); height: 36px; display: flex; align-items: center; font-size: 11.5px; color: rgba(255,255,255,.45); }
.wrap { max-width: 1260px; margin: 0 auto; padding: 0 24px; width: 100%; }
.topbar .wrap { display: flex; justify-content: space-between; align-items: center; }
.topbar a { color: rgba(255,255,255,.4); transition: color .2s; }
.topbar a:hover { color: var(--gold-lt); }
.topbar .phones { display: flex; gap: 20px; }
.topbar .phones span { display: flex; align-items: center; gap: 5px; }
.topbar .icon-phone { color: var(--gold); font-size: 10px; }
@media (max-width:600px) { .topbar .phones span:last-child { display:none; } }
.nav { position: sticky; top: 0; z-index: 900; height: 64px; background: rgba(17,16,16,.97); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); }
.nav .wrap { height: 100%; display: flex; justify-content: space-between; align-items: center; position: relative; }
.nav-brand { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.nav-brand img { height: 32px; width: auto; }
.nav-brand-text { font-family: "Cormorant Garamond", serif; font-size: 17px; font-weight: 600; color: #fff; line-height: 1.2; }
.nav-brand-text em { color: var(--gold); font-style: normal; }
.nav-menu { display: flex; align-items: center; gap: 2px; }
.nav-menu li a { display: block; padding: 6px 12px; font-size: 12.5px; font-weight: 400; color: rgba(255,255,255,.6); letter-spacing: .2px; border-radius: 3px; transition: color .2s, background .2s; white-space: nowrap; }
.nav-menu li a:hover { color: #fff; background: rgba(255,255,255,.07); }
.nav-menu li a.active { color: var(--gold-lt); }
.nav-drop { position: relative; }
.nav-drop-panel { display: none; position: absolute; top: calc(100% + 8px); left: 0; background: #1a1918; border: 1px solid var(--border); border-radius: 4px; min-width: 190px; padding: 6px 0; box-shadow: 0 16px 40px rgba(0,0,0,.4); z-index: 10; }
.nav-drop:hover .nav-drop-panel { display: block; }
.nav-drop-panel a { display: block; padding: 8px 18px; font-size: 12.5px; color: rgba(255,255,255,.6) !important; border-radius: 0 !important; background: none !important; }
.nav-drop-panel a:hover { background: rgba(184,135,42,.12) !important; color: #fff !important; }
.nav-user { font-size: 12px; color: var(--gold) !important; border: 1px solid var(--border) !important; border-radius: 20px !important; padding: 4px 14px !important; }
.nav-burger { display: none; background: none; border: 1px solid rgba(255,255,255,.2); border-radius: 3px; padding: 6px 10px; cursor: pointer; color: #fff; font-size: 18px; line-height: 1; }
@media (max-width: 900px) {
  .nav-burger { display: block; }
  .nav-menu { display: none; flex-direction: column; align-items: flex-start; position: absolute; top: 64px; left: 0; right: 0; background: #141312; border-top: 1px solid var(--border); padding: 10px 16px 20px; gap: 0; max-height: calc(100svh - 100px); overflow-y: auto; }
  .nav-menu.open { display: flex; }
  .nav-menu li { width: 100%; }
  .nav-menu li a { padding: 10px 8px; font-size: 14px; }
  .nav-drop-panel { position: static; border: none; box-shadow: none; background: rgba(255,255,255,.04); padding: 0 0 0 14px; display: block; min-width: auto; }
}
.hero { position: relative; height: 100svh; min-height: 560px; max-height: 900px; overflow: hidden; background: var(--ink); }
.hero-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 1.2s var(--ease); will-change: opacity; }
.hero-slide.active { opacity: 1; }
.hero-slide img { width: 100%; height: 100%; object-fit: cover; transform: scale(1.05); transition: transform 7s var(--ease); will-change: transform; }
.hero-slide.active img { transform: scale(1); }
.hero-slide::after { content: ""; position: absolute; inset: 0; background: linear-gradient(100deg, rgba(10,9,9,.85) 0%, rgba(10,9,9,.3) 55%, transparent 100%), linear-gradient(0deg, rgba(10,9,9,.5) 0%, transparent 45%); }
.hero-content { position: absolute; inset: 0; z-index: 2; display: flex; align-items: center; pointer-events: none; }
.hero-content .wrap { position: relative; }
.hero-text { position: absolute; top: 50%; left: 0; transform: translateY(-50%); max-width: 560px; width: 100%; opacity: 0; transition: opacity .8s var(--ease); will-change: opacity; pointer-events: none; }
.hero-text:first-child { position: relative; top: auto; left: auto; transform: none; visibility: hidden; }
.hero-text:first-child.active { visibility: visible; }
.hero-text.active { opacity: 1; pointer-events: auto; }
.hero-tag { display: inline-flex; align-items: center; gap: 8px; font-size: 10px; font-weight: 500; color: var(--gold); text-transform: uppercase; letter-spacing: 2.5px; margin-bottom: 16px; }
.hero-tag::before { content: ""; width: 22px; height: 1px; background: var(--gold); display: block; }
.hero-title { font-family: "Cormorant Garamond", serif; font-size: clamp(34px, 5vw, 64px); font-weight: 700; color: #fff; line-height: 1.08; letter-spacing: -.3px; margin-bottom: 14px; }
.hero-desc { font-size: 14px; color: rgba(255,255,255,.6); line-height: 1.7; margin-bottom: 24px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.hero-price { font-family: "Cormorant Garamond", serif; font-size: 26px; font-weight: 600; color: var(--gold-lt); display: block; margin-bottom: 26px; }
.hero-cta { display: inline-flex; align-items: center; gap: 10px; background: var(--gold); color: #fff; font-size: 11.5px; font-weight: 500; letter-spacing: 1.2px; text-transform: uppercase; padding: 13px 26px; border-radius: 2px; transition: background .25s, transform .25s; }
.hero-cta:hover { background: var(--gold-lt); color: #fff; transform: translateY(-2px); }
.hero-cta-arrow { display: inline-block; width: 16px; height: 1px; background: rgba(255,255,255,.7); position: relative; vertical-align: middle; transition: width .25s; }
.hero-cta-arrow::after { content: ""; position: absolute; right: -1px; top: -3px; border: 4px solid transparent; border-left: 5px solid rgba(255,255,255,.7); }
.hero-cta:hover .hero-cta-arrow { width: 22px; }
.hero-dots { position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 3; display: flex; gap: 8px; align-items: center; }
.hero-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,.25); border: none; cursor: pointer; padding: 0; transition: background .3s, transform .3s; }
.hero-dot.active { background: var(--gold); transform: scale(1.5); }
.hero-loc { position: absolute; bottom: 24px; right: 24px; z-index: 3; font-size: 11px; color: rgba(255,255,255,.4); display: flex; align-items: center; gap: 5px; letter-spacing: .4px; }
.hero-loc .icon-my_location { color: var(--gold); font-size: 11px; }
@media (max-width:560px) { .hero-loc { display: none; } }
.features { display: grid; grid-template-columns: repeat(4, 1fr); background: var(--border); gap: 1px; border: 1px solid var(--border); }
@media (max-width:860px) { .features { grid-template-columns: repeat(2,1fr); } }
@media (max-width:480px) { .features { grid-template-columns: 1fr; } }
.feature { background: var(--white); padding: 36px 26px; transition: background .25s; }
.feature:hover { background: var(--cream); }
.feature-num { font-family: "Cormorant Garamond", serif; font-size: 44px; font-weight: 700; color: var(--gold); opacity: .2; line-height: 1; margin-bottom: 14px; }
.feature-icon { width: 42px; height: 42px; border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
.feature-icon span { font-size: 17px; color: var(--gold); }
.feature h3 { font-family: "Cormorant Garamond", serif; font-size: 17px; font-weight: 600; color: var(--ink); margin-bottom: 8px; }
.feature p { font-size: 13px; color: var(--muted); line-height: 1.65; }
.section { padding: 80px 0; }
.section-alt { background: var(--cream); }
.section-warm { background: var(--warm); }
.section-eyebrow { display: flex; align-items: center; gap: 10px; font-size: 10px; font-weight: 500; color: var(--gold); text-transform: uppercase; letter-spacing: 2.5px; margin-bottom: 12px; }
.section-eyebrow::after { content: ""; height: 1px; background: var(--border); width: 48px; }
.section-title { font-family: "Cormorant Garamond", serif; font-size: clamp(26px, 3.2vw, 42px); font-weight: 700; color: var(--ink); line-height: 1.15; }
.section-title em { color: var(--gold); font-style: italic; }
.prop-card { background: var(--white); border: 1px solid rgba(0,0,0,.07); overflow: hidden; transition: box-shadow .3s var(--ease), transform .3s var(--ease); }
.prop-card:hover { box-shadow: 0 14px 40px rgba(0,0,0,.1); transform: translateY(-4px); }
.prop-img { position: relative; overflow: hidden; height: 208px; background: var(--warm); }
.prop-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s var(--ease); }
.prop-card:hover .prop-img img { transform: scale(1.06); }
.prop-badge { position: absolute; top: 12px; left: 12px; background: var(--gold); color: #fff; font-size: 9.5px; font-weight: 500; letter-spacing: 1px; text-transform: uppercase; padding: 4px 10px; }
.prop-body { padding: 18px 18px 16px; }
.prop-body h3 { font-family: "Cormorant Garamond", serif; font-size: 18px; font-weight: 600; color: var(--ink); margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.prop-body h3 a { color: inherit; transition: color .2s; }
.prop-body h3 a:hover { color: var(--gold); }
.prop-cat { font-size: 11.5px; color: var(--muted); margin-bottom: 12px; }
.prop-price { font-family: "Cormorant Garamond", serif; font-size: 21px; font-weight: 600; color: var(--gold); }
.prop-meta { display: flex; gap: 14px; margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,.06); font-size: 11.5px; color: var(--muted); }
.prop-meta span { display: flex; align-items: center; gap: 4px; }
.prop-meta i { color: var(--gold); font-size: 12px; }
.slider-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 36px; gap: 16px; flex-wrap: wrap; }
.slider-nav { display: flex; gap: 8px; }
.slider-btn { width: 40px; height: 40px; border: 1px solid var(--border); background: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 17px; color: var(--ink); transition: background .2s, color .2s, border-color .2s; flex-shrink: 0; }
.slider-btn:hover { background: var(--gold); color: #fff; border-color: var(--gold); }
.slider-outer { overflow: hidden; }
.slider-track { display: flex; gap: 24px; transition: transform .45s var(--ease); will-change: transform; }
.slider-track .prop-card { flex: 0 0 calc(33.333% - 16px); }
@media (max-width:860px) { .slider-track .prop-card { flex: 0 0 calc(50% - 12px); } }
@media (max-width:540px) { .slider-track .prop-card { flex: 0 0 100%; } }
.recoms { display: grid; grid-template-columns: repeat(4,1fr); }
.recoms .prop-card { border-radius: 0; border: none; border-right: 1px solid rgba(0,0,0,.06); border-bottom: 1px solid rgba(0,0,0,.06); }
@media (max-width:860px) { .recoms { grid-template-columns: repeat(2,1fr); } }
@media (max-width:480px) { .recoms { grid-template-columns: 1fr; } }
.footer { background: var(--ink); color: rgba(255,255,255,.45); padding: 68px 0 0; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1.7fr; gap: 56px; padding-bottom: 56px; border-bottom: 1px solid rgba(255,255,255,.07); }
@media (max-width:820px) { .footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; } }
@media (max-width:480px) { .footer-grid { grid-template-columns: 1fr; } }
.footer h4 { font-family: "Cormorant Garamond", serif; font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 18px; }
.footer p { font-size: 13px; line-height: 1.75; }
.footer-links li { margin-bottom: 9px; }
.footer-links a { font-size: 13px; color: rgba(255,255,255,.4); transition: color .2s; }
.footer-links a:hover { color: var(--gold-lt); }
.footer-contact li { display: flex; gap: 10px; font-size: 13px; line-height: 1.65; margin-bottom: 11px; }
.footer-contact .fi { color: var(--gold); flex-shrink: 0; margin-top: 2px; }
.footer-social { display: flex; gap: 10px; margin-top: 20px; }
.footer-social a { width: 34px; height: 34px; border: 1px solid rgba(255,255,255,.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; color: rgba(255,255,255,.4); transition: border-color .2s, color .2s; }
.footer-social a:hover { border-color: var(--gold); color: var(--gold); }
.footer-bottom { padding: 18px 0; text-align: center; font-size: 11.5px; color: rgba(255,255,255,.18); letter-spacing: .3px; }
#loader { position: fixed; inset: 0; z-index: 9999; background: var(--white); display: flex; align-items: center; justify-content: center; transition: opacity .5s var(--ease); }
#loader.gone { opacity: 0; pointer-events: none; }
.loader-ring { width: 34px; height: 34px; border: 2px solid var(--warm); border-top-color: var(--gold); border-radius: 50%; animation: spin .7s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="features">
  <div class="feature"><h3>Encuentra el lugar ideal</h3><p>Gran variedad de inmuebles en Colima y sus alrededores para cada necesidad.</p></div>
  <div class="feature"><h3>Agentes con experiencia</h3><p>Especialistas listos para orientarte en cada paso del proceso.</p></div>
  <div class="feature"><h3>Compra y renta</h3><p>Catalogo amplio de propiedades en venta y renta a tu medida.</p></div>
  <div class="feature"><h3>Cuida tu inversion</h3><p>Manzanillo crece constantemente. Invertir aqui es siempre un acierto.</p></div>
</div>

<section class="section" id="nuevas">
  <div class="wrap">
    <div class="slider-head">
      <div>
        <div class="section-eyebrow">Publicaciones recientes</div>
        <h2 class="section-title">Nuevas <em>propiedades</em></h2>
      </div>
      <div class="slider-nav">
        <button class="slider-btn" id="sl-prev">&#8592;</button>
        <button class="slider-btn" id="sl-next">&#8594;</button>
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
            </div>
            <div class="prop-body">
              <h3><a href="{{ route("propiedades.show", $p->slug) }}">{{ $p->titulo }}</a></h3>
              <p class="prop-cat">{{ $p->tipo?->nombre }}</p>
              <span class="prop-price">${{ number_format($p->precio, 0) }} {{ $p->moneda }}</span>
              <div class="prop-meta">
                @if ($p->m2_construccion)<span>{{ $p->m2_construccion }} m2</span>@endif
                @if ($p->banios)<span>{{ $p->banios }} banos</span>@endif
                @if ($p->recamaras)<span>{{ $p->recamaras }} rec</span>@endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="wrap" style="margin-bottom:40px;">
    <div class="section-eyebrow">Seleccion especial</div>
    <h2 class="section-title">Propiedades <em>recomendadas</em></h2>
  </div>
  <div class="recoms">
    @foreach ($recomendadas as $p)
      <div class="prop-card">
        <div class="prop-img" style="height:228px;">
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
        </div>
        <div class="prop-body">
          <h3><a href="{{ route("propiedades.show", $p->slug) }}">{{ $p->titulo }}</a></h3>
          <p class="prop-cat">{{ $p->tipo?->nombre }}</p>
          <span class="prop-price">${{ number_format($p->precio, 0) }} {{ $p->moneda }}</span>
          <div class="prop-meta">
            @if ($p->m2_construccion)<span>{{ $p->m2_construccion }} m2</span>@endif
            @if ($p->banios)<span>{{ $p->banios }} banos</span>@endif
            @if ($p->recamaras)<span>{{ $p->recamaras }} rec</span>@endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>

<footer class="footer" id="contacto">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <h4>Ochoa Real Estate Services</h4>
        <p>Gran variedad de bienes inmuebles en el Puerto de Manzanillo. Invertir en este hermoso lugar es siempre un acierto.</p>
        <div class="footer-social">
          <a href="https://www.facebook.com/ochoainmobiliaria/" target="_blank"><span class="icon-facebook"></span></a>
        </div>
      </div>
      <div>
        <h4>Informacion</h4>
        <ul class="footer-links">
          <li><a href="/">Inicio</a></li>
          <li><a href="#nuevas">Propiedades</a></li>
        </ul>
      </div>
      <div>
        <h4>Contactanos</h4>
        <ul class="footer-contact">
          <li><span class="fi icon-map-marker"></span><span>Lluvia de Oro 57, Arboledas, 28869 Manzanillo, Col.</span></li>
          <li><span class="fi icon-phone"></span><span>+52 (314) 333-3202 Oficina</span></li>
          <li><span class="fi icon-phone"></span><span>+52 (314) 376-9162 Celular</span></li>
          <li><span class="fi icon-envelope"></span><span>inmobiliariaochoa@hotmail.com</span></li>
          <li><span class="fi"></span><span>Lun-Vie: 8:00-14:00 y 16:00-19:00<br>Sab: 9:00-14:00</span></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="wrap">
      Todos los Derechos Reservados &copy; {{ date("Y") }} - Ochoa Real Estate Services
    </div>
  </div>
</footer>

<script>
document.getElementById("burger").addEventListener("click", function(e) {
  e.stopPropagation();
  document.getElementById("nav-menu").classList.toggle("open");
});
document.addEventListener("click", function() {
  document.getElementById("nav-menu").classList.remove("open");
});

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

(function() {
  var track = document.getElementById("slider-track");
  var btnP = document.getElementById("sl-prev");
  var btnN = document.getElementById("sl-next");
  if (!track) return;
  var pos = 0;
  function visCount() {
    if (window.innerWidth < 540) return 1;
    if (window.innerWidth < 860) return 2;
    return 3;
  }
  function maxPos() {
    return Math.max(0, track.children.length - visCount());
  }
  function move(dir) {
    pos = Math.max(0, Math.min(pos + dir, maxPos()));
    track.style.transform = "translateX(-" + (pos * (track.offsetWidth / visCount() + 8)) + "px)";
  }
  btnP.addEventListener("click", function() { move(-1); });
  btnN.addEventListener("click", function() { move(1); });
})();
</script>

</body>
</html>
