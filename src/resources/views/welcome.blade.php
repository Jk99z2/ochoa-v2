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

<style>
:root { --ink: #111010; --gold: #b8872a; --gold-lt: #d4a84b; --gold-soft: rgba(184,135,42,.1); --cream: #f5f1ea; --warm: #ede8de; --muted: #7a7468; --white: #ffffff; --border: rgba(184,135,42,.18); --radius: 14px; --radius-sm: 8px; --shadow-soft: 0 10px 30px rgba(17,16,16,.06); --shadow-lift: 0 20px 50px rgba(17,16,16,.12); --ease: cubic-bezier(.4,0,.2,1); }
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }
body { font-family: "Inter", sans-serif; background: var(--white); color: var(--ink); overflow-x: hidden; }
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
@media (max-width:820px) { .topbar .phones a:last-child { display:none; } }
.nav { position: sticky; top: 0; z-index: 900; height: 64px; background: rgba(17,16,16,.97); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); }
.nav .wrap { height: 100%; display: flex; justify-content: space-between; align-items: center; position: relative; }
.nav-brand { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.nav-brand img { height: 32px; width: auto; }
.nav-brand-text { font-family: "Inter", sans-serif; font-size: 17px; font-weight: 600; color: #fff; line-height: 1.2; }
.nav-brand-text em { color: var(--gold); font-style: normal; }
.nav-menu { display: flex; align-items: center; gap: 2px; padding-top: 18px; }
.nav-menu li a { display: flex; align-items: center; height: 40px; padding: 0 12px; font-size: 12.5px; font-weight: 400; color: rgba(255,255,255,.6); letter-spacing: .2px; border-radius: 3px; transition: color .2s, background .2s; white-space: nowrap; }
.nav-menu li a:hover { color: #fff; background: rgba(255,255,255,.07); }
.nav-menu li a.active { color: var(--gold-lt); }
.nav-drop { position: relative; }
.nav-drop-panel { display: none; position: absolute; top: 100%; left: 0; background: #1a1918; border: 1px solid var(--border); border-radius: 4px; min-width: 190px; padding: 6px 0; box-shadow: 0 16px 40px rgba(0,0,0,.4); z-index: 10; }
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
.hero-tag { display: inline-flex; align-items: center; gap: 8px; font-size: 10px; font-weight: 500; color: var(--gold-lt); text-transform: uppercase; letter-spacing: 2.5px; margin-bottom: 16px; }
.hero-tag::before { content: ""; width: 22px; height: 1px; background: var(--gold-lt); display: block; }
.hero-title { font-family: "Inter", sans-serif; font-size: clamp(34px, 5vw, 64px); font-weight: 700; color: #fff; line-height: 1.08; letter-spacing: -.3px; margin-bottom: 14px; }
.hero-desc { font-size: 14px; color: rgba(255,255,255,.6); line-height: 1.7; margin-bottom: 24px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.hero-price { font-family: "Inter", sans-serif; font-size: 26px; font-weight: 600; color: var(--gold-lt); display: block; margin-bottom: 26px; }
.hero-cta { display: inline-flex; align-items: center; gap: 10px; background: var(--gold); color: #fff; font-size: 11.5px; font-weight: 500; letter-spacing: 1.2px; text-transform: uppercase; padding: 13px 26px; border-radius: 4px; transition: background .25s, transform .25s, box-shadow .25s; box-shadow: 0 10px 30px rgba(184,135,42,.25); }
.hero-cta:hover { background: var(--gold-lt); color: #fff; transform: translateY(-2px); box-shadow: 0 14px 34px rgba(184,135,42,.35); }
.hero-cta-arrow { display: inline-block; width: 16px; height: 1px; background: rgba(255,255,255,.7); position: relative; vertical-align: middle; transition: width .25s; }
.hero-cta-arrow::after { content: ""; position: absolute; right: -1px; top: -3px; border: 4px solid transparent; border-left: 5px solid rgba(255,255,255,.7); }
.hero-cta:hover .hero-cta-arrow { width: 22px; }
.hero-dots { position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 3; display: flex; gap: 8px; align-items: center; }
.hero-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,.25); border: none; cursor: pointer; padding: 0; transition: background .3s, transform .3s; }
.hero-dot.active { background: var(--gold); transform: scale(1.5); }
.hero-loc { position: absolute; bottom: 24px; right: 24px; z-index: 3; font-size: 11px; color: rgba(255,255,255,.4); display: flex; align-items: center; gap: 5px; letter-spacing: .4px; }
.hero-loc .icon-my_location { color: var(--gold); font-size: 11px; }
@media (max-width:560px) { .hero-loc { display: none; } }
.hero-search-wrap { position: relative; z-index: 5; margin-top: -44px; margin-bottom: 40px; }
.hero-search { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow-lift); padding: 22px 26px; display: grid; grid-template-columns: repeat(4, 1fr) auto; gap: 0; align-items: end; }
@media (max-width: 900px) { .hero-search { grid-template-columns: repeat(2, 1fr); gap: 16px; } }
@media (max-width: 560px) { .hero-search { grid-template-columns: 1fr; gap: 14px; } }
.hs-field { display: flex; flex-direction: column; padding: 0 20px; border-left: 1px solid rgba(0,0,0,.07); }
.hs-field:first-child { padding-left: 0; border-left: none; }
@media (max-width: 900px) { .hs-field { border-left: none; padding: 0; } }
.hs-field label { font-size: 10.5px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
.hs-field select, .hs-field input { border: none; padding: 4px 0; font-size: 14px; font-family: inherit; background: transparent; color: var(--ink); }
.hs-field select:focus, .hs-field input:focus { outline: none; }
@media (max-width: 900px) { .hs-field select, .hs-field input { border: 1px solid rgba(0,0,0,.12); border-radius: var(--radius-sm); padding: 10px; } }
.hs-submit { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--gold); color: #fff; border: none; padding: 14px 28px; margin-left: 20px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 500; letter-spacing: .5px; text-transform: uppercase; cursor: pointer; transition: background .2s, transform .2s; white-space: nowrap; }
.hs-submit:hover { background: var(--gold-lt); transform: translateY(-1px); }
.hs-submit-icon { width: 15px; height: 15px; flex-shrink: 0; }
@media (max-width: 900px) { .hs-submit { margin-left: 0; grid-column: 1 / -1; padding: 13px; } }
.features { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
@media (max-width:860px) { .features { grid-template-columns: repeat(2,1fr); } }
@media (max-width:480px) { .features { grid-template-columns: 1fr; } }
.feature { background: var(--white); border: 1px solid rgba(0,0,0,.06); border-radius: var(--radius); padding: 30px 26px; transition: box-shadow .3s var(--ease), transform .3s var(--ease), border-color .3s; }
.feature:hover { box-shadow: var(--shadow-soft); transform: translateY(-4px); border-color: transparent; }
.feature-icon { width: 46px; height: 46px; border-radius: 50%; background: var(--gold-soft); display: flex; align-items: center; justify-content: center; margin-bottom: 18px; transition: background .3s; }
.feature:hover .feature-icon { background: var(--gold); }
.feature-icon svg { width: 21px; height: 21px; color: var(--gold); transition: color .3s; }
.feature:hover .feature-icon svg { color: #fff; }
.feature h3 { font-family: "Inter", sans-serif; font-size: 16.5px; font-weight: 600; color: var(--ink); margin-bottom: 8px; }
.feature p { font-size: 13px; color: var(--muted); line-height: 1.65; }
.section { padding: 88px 0; }
.section-alt { background: var(--cream); }
.section-warm { background: var(--warm); }
.section-eyebrow { display: flex; align-items: center; gap: 10px; font-size: 10px; font-weight: 500; color: var(--gold); text-transform: uppercase; letter-spacing: 2.5px; margin-bottom: 12px; }
.section-eyebrow::after { content: ""; height: 1px; background: var(--border); width: 48px; }
.section-title { font-family: "Inter", sans-serif; font-size: clamp(26px, 3.2vw, 42px); font-weight: 700; color: var(--ink); line-height: 1.15; }
.section-title em { color: var(--gold); font-style: italic; }
.section-desc { font-size: 14px; color: var(--muted); line-height: 1.7; max-width: 460px; margin-top: 14px; }
.prop-card { background: var(--white); border: 1px solid rgba(0,0,0,.07); border-radius: var(--radius); overflow: hidden; transition: box-shadow .3s var(--ease), transform .3s var(--ease); }
.prop-card:hover { box-shadow: var(--shadow-lift); transform: translateY(-6px); }
.prop-img { position: relative; overflow: hidden; height: 208px; background: var(--warm); }
.prop-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s var(--ease); }
.prop-card:hover .prop-img img { transform: scale(1.06); }
.prop-badge { position: absolute; top: 12px; left: 12px; background: var(--gold); color: #fff; font-size: 9.5px; font-weight: 500; letter-spacing: 1px; text-transform: uppercase; padding: 5px 12px; border-radius: 30px; }
.prop-municipio { position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,.94); color: var(--ink); font-size: 9.5px; font-weight: 600; letter-spacing: .3px; border-radius: 30px; padding: 5px 12px; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,0,0,.15); }
.prop-body { padding: 20px 20px 18px; }
.prop-body h3 { font-family: "Inter", sans-serif; font-size: 18px; font-weight: 600; color: var(--ink); margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.prop-body h3 a { color: inherit; transition: color .2s; }
.prop-body h3 a:hover { color: var(--gold); }
.prop-cat { font-size: 11.5px; color: var(--muted); margin-bottom: 12px; }
.prop-price { font-family: "Inter", sans-serif; font-size: 21px; font-weight: 600; color: var(--gold); }
.prop-meta { display: flex; gap: 14px; margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(0,0,0,.06); font-size: 11.5px; color: var(--muted); }
.prop-meta span { display: flex; align-items: center; gap: 4px; }
.prop-meta i { color: var(--gold); font-size: 12px; }
.slider-head { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 36px; gap: 16px; flex-wrap: wrap; }
.slider-nav { display: flex; gap: 8px; }
.slider-btn { width: 42px; height: 42px; border: 1px solid var(--border); background: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 17px; color: var(--ink); transition: background .2s, color .2s, border-color .2s, box-shadow .2s; flex-shrink: 0; }
.slider-btn:hover { background: var(--gold); color: #fff; border-color: var(--gold); box-shadow: 0 8px 20px rgba(184,135,42,.3); }
.slider-btn:disabled { opacity: .35; cursor: default; box-shadow: none; }
.slider-btn:disabled:hover { background: var(--white); color: var(--ink); border-color: var(--border); }
.slider-outer { overflow: hidden; }
.slider-track { display: flex; gap: 24px; transition: transform .5s var(--ease); will-change: transform; }
.slider-track .prop-card { flex: 0 0 calc(33.333% - 16px); }
@media (max-width:860px) { .slider-track .prop-card { flex: 0 0 calc(50% - 12px); } }
@media (max-width:540px) { .slider-track .prop-card { flex: 0 0 100%; } }
.slider-dots { display: flex; justify-content: center; gap: 8px; margin-top: 32px; }
.slider-dots:empty { display: none; }
.slider-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(17,16,16,.15); border: none; cursor: pointer; padding: 0; transition: background .3s, transform .3s; }
.slider-dot.active { background: var(--gold); transform: scale(1.5); }
.recoms { display: grid; grid-template-columns: repeat(4,1fr); gap: 24px; }
@media (max-width:860px) { .recoms { grid-template-columns: repeat(2,1fr); } }
@media (max-width:480px) { .recoms { grid-template-columns: 1fr; } }
.footer { background: var(--ink); color: rgba(255,255,255,.45); padding: 76px 0 0; }
.footer-grid { display: grid; grid-template-columns: 2fr 1fr 1.7fr; gap: 56px; padding-bottom: 56px; border-bottom: 1px solid rgba(255,255,255,.07); }
@media (max-width:820px) { .footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; } }
@media (max-width:480px) { .footer-grid { grid-template-columns: 1fr; } }
.footer h4 { font-family: "Inter", sans-serif; font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 18px; }
.footer p { font-size: 13px; line-height: 1.75; }
.footer-links li { margin-bottom: 9px; }
.footer-links a { font-size: 13px; color: rgba(255,255,255,.4); transition: color .2s; }
.footer-links a:hover { color: var(--gold-lt); }
.footer-contact li { display: flex; align-items: flex-start; gap: 12px; font-size: 13px; line-height: 1.65; margin-bottom: 14px; }
.footer-contact .fi { flex-shrink: 0; width: 26px; height: 26px; border-radius: 50%; background: rgba(184,135,42,.14); color: var(--gold); display: flex; align-items: center; justify-content: center; font-size: 11px; margin-top: 1px; }
.footer-social { display: flex; gap: 10px; margin-top: 20px; }
.footer-social a { width: 34px; height: 34px; border: 1px solid rgba(255,255,255,.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; color: rgba(255,255,255,.4); transition: border-color .2s, color .2s; }
.footer-social a:hover { border-color: var(--gold); color: var(--gold); }
.footer-bottom { padding: 18px 0; text-align: center; font-size: 11.5px; color: rgba(255,255,255,.18); letter-spacing: .3px; }
#loader { position: fixed; inset: 0; z-index: 9999; background: var(--white); display: flex; align-items: center; justify-content: center; transition: opacity .5s var(--ease); }
#loader.gone { opacity: 0; pointer-events: none; }
.loader-ring { width: 34px; height: 34px; border: 2px solid var(--warm); border-top-color: var(--gold); border-radius: 50%; animation: spin .7s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
@media (max-width: 640px) {
  .hero-search-wrap { margin-top: -24px; }
  .hero-search { padding: 18px; }
  .section { padding: 56px 0; }
  .hero-title { font-size: 28px; }
  .hero-desc { -webkit-line-clamp: 3; }
}
.contact-section{padding:78px 0;background:var(--cream)}
.contact-lead{font-size:14px;color:var(--muted);margin:12px 0 28px}
.contact-form{max-width:600px}
.cf-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
@media (max-width:560px){.cf-row{grid-template-columns:1fr}}
.contact-form input,.contact-form textarea{width:100%;padding:13px 14px;border:1px solid rgba(0,0,0,.1);border-radius:var(--radius-sm);font-size:13.5px;font-family:inherit;background:var(--white);color:var(--ink);resize:vertical;transition:border-color .2s,box-shadow .2s}
.contact-form input:focus,.contact-form textarea:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px var(--gold-soft)}
.contact-form textarea{margin-bottom:14px}
.lead-error{font-size:11.5px;color:#b83232;margin:-8px 0 14px}
.alert-success{background:#2e7d4f;color:#fff;padding:14px 0;font-size:13.5px;text-align:center}
.contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:stretch}
@media (max-width:820px){.contact-grid{grid-template-columns:1fr}}
.contact-col-map{min-height:360px;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-soft)}
.contact-col-map iframe{width:100%;height:100%;min-height:360px;display:block}
</style>

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

<section class="section section-alt">
  <div class="wrap" style="margin-bottom:40px;">
    <div class="section-eyebrow">Seleccion especial</div>
    <h2 class="section-title">Propiedades <em>recomendadas</em></h2>
    <p class="section-desc">Una curaduria de las mejores opciones disponibles ahora mismo en Manzanillo.</p>
  </div>
  <div class="wrap recoms">
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

(function() {
  var track = document.getElementById("slider-track");
  var btnP = document.getElementById("sl-prev");
  var btnN = document.getElementById("sl-next");
  var dotsWrap = document.getElementById("slider-dots");
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
})();
</script>

</body>
</html>
