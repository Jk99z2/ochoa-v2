<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $propiedad->titulo }} - Ochoa Real Estate Services</title>
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
      <li><a href="/">Inicio</a></li>
      <li><a href="/#nuevas">Propiedades</a></li>
      <li><a href="/#contacto">Contacto</a></li>
    </ul>
  </div>
</nav>

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
        @if ($propiedad->destacada)
          <span class="prop-badge">Destacada</span>
        @endif
        <h1 class="detail-title">{{ $propiedad->titulo }}</h1>
        <p class="detail-loc">{{ $propiedad->colonia }}@if($propiedad->colonia && $propiedad->ciudad), @endif{{ $propiedad->ciudad }}, {{ $propiedad->estado_mx }}</p>
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
      @elseif ($propiedad->ciudad)
        <div class="detail-section">
          <h2 class="detail-subtitle">Ubicacion</h2>
          <p class="detail-desc">{{ $propiedad->colonia }}@if($propiedad->colonia), @endif{{ $propiedad->ciudad }}, {{ $propiedad->estado_mx }}</p>
        </div>
      @endif
    </div>

    <aside class="detail-sidebar">
      <div class="agent-card">
        <h3 class="agent-card-title">Contacta al agente</h3>
        @if ($propiedad->agente)
          <div class="agent-info">
            @if ($propiedad->agente->foto)
              <img src="{{ Storage::url($propiedad->agente->foto) }}" alt="{{ $propiedad->agente->nombre }}" class="agent-photo">
            @endif
            <div>
              <p class="agent-name">{{ $propiedad->agente->nombre }}</p>
              @if ($propiedad->agente->telefono)<p class="agent-contact">{{ $propiedad->agente->telefono }}</p>@endif
              @if ($propiedad->agente->email)<p class="agent-contact">{{ $propiedad->agente->email }}</p>@endif
            </div>
          </div>
          @if ($propiedad->agente->whatsapp)
            <a href="https://wa.me/{{ preg_replace("/[^0-9]/", "", $propiedad->agente->whatsapp) }}" target="_blank" class="agent-cta">Contactar por WhatsApp</a>
          @endif
        @else
          <p class="agent-contact">+52 (314) 333-3202</p>
          <p class="agent-contact">+52 (314) 376-9162</p>
        @endif
      </div>
    </aside>
  </div>
</section>

<style>
:root { --ink:#111010; --gold:#b8872a; --gold-lt:#d4a84b; --cream:#f5f1ea; --warm:#ede8de; --muted:#7a7468; --white:#ffffff; --border:rgba(184,135,42,.18); --ease:cubic-bezier(.4,0,.2,1); }
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%}
body{font-family:"DM Sans",sans-serif;background:var(--white);color:var(--ink);overflow-x:hidden}
a{text-decoration:none;color:inherit} img{display:block;max-width:100%} ul{list-style:none}
.topbar{background:var(--ink);height:36px;display:flex;align-items:center;font-size:11.5px;color:rgba(255,255,255,.45)}
.wrap{max-width:1260px;margin:0 auto;padding:0 24px;width:100%}
.topbar .wrap{display:flex;justify-content:space-between;align-items:center}
.topbar a{color:rgba(255,255,255,.4);transition:color .2s} .topbar a:hover{color:var(--gold-lt)}
.topbar .phones{display:flex;gap:20px} .topbar .phones span{display:flex;align-items:center;gap:5px}
@media (max-width:600px){.topbar .phones span:last-child{display:none}}
.nav{position:sticky;top:0;z-index:900;height:64px;background:rgba(17,16,16,.97);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
.nav .wrap{height:100%;display:flex;justify-content:space-between;align-items:center;position:relative}
.nav-brand{display:flex;align-items:center;gap:10px;flex-shrink:0} .nav-brand img{height:32px;width:auto}
.nav-brand-text{font-family:"Cormorant Garamond",serif;font-size:17px;font-weight:600;color:#fff;line-height:1.2}
.nav-brand-text em{color:var(--gold);font-style:normal}
.nav-menu{display:flex;align-items:center;gap:2px}
.nav-menu li a{display:block;padding:6px 12px;font-size:12.5px;color:rgba(255,255,255,.6);border-radius:3px;transition:color .2s,background .2s;white-space:nowrap}
.nav-menu li a:hover{color:#fff;background:rgba(255,255,255,.07)}
.nav-burger{display:none;background:none;border:1px solid rgba(255,255,255,.2);border-radius:3px;padding:6px 10px;cursor:pointer;color:#fff;font-size:14px}
@media (max-width:900px){
  .nav-burger{display:block}
  .nav-menu{display:none;flex-direction:column;align-items:flex-start;position:absolute;top:64px;left:0;right:0;background:#141312;border-top:1px solid var(--border);padding:10px 16px 20px}
  .nav-menu.open{display:flex} .nav-menu li{width:100%} .nav-menu li a{padding:10px 8px;font-size:14px}
}
.detail-gallery{padding:24px 0 0;}
.gallery-main{width:100%;height:460px;background:var(--warm);border-radius:6px;overflow:hidden;margin-bottom:12px}
.gallery-main img{width:100%;height:100%;object-fit:cover}
.gallery-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted)}
.gallery-thumbs{display:flex;gap:10px;overflow-x:auto;padding-bottom:8px}
.gallery-thumb{width:110px;height:80px;object-fit:cover;border-radius:4px;cursor:pointer;flex-shrink:0;opacity:.65;transition:opacity .2s;border:2px solid transparent}
.gallery-thumb:hover,.gallery-thumb.active{opacity:1;border-color:var(--gold)}
.detail-body{padding:56px 0 80px}
.detail-grid{display:grid;grid-template-columns:2fr 1fr;gap:48px}
@media (max-width:900px){.detail-grid{grid-template-columns:1fr}}
.prop-badge{display:inline-block;background:var(--gold);color:#fff;font-size:9.5px;font-weight:500;letter-spacing:1px;text-transform:uppercase;padding:4px 10px;margin-bottom:12px}
.detail-title{font-family:"Cormorant Garamond",serif;font-size:clamp(28px,3.5vw,42px);font-weight:700;color:var(--ink);line-height:1.15;margin-bottom:8px}
.detail-loc{font-size:14px;color:var(--muted);margin-bottom:16px}
.detail-price{font-family:"Cormorant Garamond",serif;font-size:32px;font-weight:600;color:var(--gold)}
.detail-price-period{font-size:15px;font-weight:400;color:var(--muted)}
.detail-stats{display:flex;flex-wrap:wrap;gap:28px;padding:24px 0;margin:28px 0;border-top:1px solid rgba(0,0,0,.08);border-bottom:1px solid rgba(0,0,0,.08)}
.stat{display:flex;flex-direction:column}
.stat-num{font-family:"Cormorant Garamond",serif;font-size:24px;font-weight:700;color:var(--ink)}
.stat-label{font-size:11.5px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}
.detail-section{margin-bottom:32px}
.detail-subtitle{font-family:"Cormorant Garamond",serif;font-size:22px;font-weight:600;color:var(--ink);margin-bottom:14px}
.detail-desc{font-size:14.5px;color:var(--muted);line-height:1.8}
.amenidades-list{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
.amenidades-list li{font-size:13.5px;color:var(--ink);padding-left:18px;position:relative}
.amenidades-list li::before{content:"";position:absolute;left:0;top:8px;width:6px;height:6px;border-radius:50%;background:var(--gold)}
.detail-sidebar{position:relative}
.agent-card{background:var(--cream);border-radius:6px;padding:28px;position:sticky;top:88px}
.agent-card-title{font-family:"Cormorant Garamond",serif;font-size:18px;font-weight:600;margin-bottom:18px}
.agent-info{display:flex;gap:14px;align-items:center;margin-bottom:18px}
.agent-photo{width:56px;height:56px;border-radius:50%;object-fit:cover}
.agent-name{font-weight:600;font-size:14.5px;margin-bottom:4px}
.agent-contact{font-size:13px;color:var(--muted)}
.agent-cta{display:block;text-align:center;background:var(--gold);color:#fff;font-size:12px;font-weight:500;letter-spacing:.5px;text-transform:uppercase;padding:12px;border-radius:3px;transition:background .2s}
.agent-cta:hover{background:var(--gold-lt)}
.footer{background:var(--ink);color:rgba(255,255,255,.45);padding:68px 0 0;margin-top:40px}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1.7fr;gap:56px;padding-bottom:56px;border-bottom:1px solid rgba(255,255,255,.07)}
@media (max-width:820px){.footer-grid{grid-template-columns:1fr 1fr;gap:32px}}
@media (max-width:480px){.footer-grid{grid-template-columns:1fr}}
.footer h4{font-family:"Cormorant Garamond",serif;font-size:18px;font-weight:600;color:#fff;margin-bottom:18px}
.footer p{font-size:13px;line-height:1.75}
.footer-links li{margin-bottom:9px}
.footer-links a{font-size:13px;color:rgba(255,255,255,.4);transition:color .2s} .footer-links a:hover{color:var(--gold-lt)}
.footer-contact li{display:flex;gap:10px;font-size:13px;line-height:1.65;margin-bottom:11px}
.footer-contact .fi{color:var(--gold);flex-shrink:0;margin-top:2px}
.footer-social{display:flex;gap:10px;margin-top:20px}
.footer-social a{width:34px;height:34px;border:1px solid rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;color:rgba(255,255,255,.4)}
.footer-bottom{padding:18px 0;text-align:center;font-size:11.5px;color:rgba(255,255,255,.18)}
</style>

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
          <li><a href="/#nuevas">Propiedades</a></li>
        </ul>
      </div>
      <div>
        <h4>Contactanos</h4>
        <ul class="footer-contact">
          <li><span class="fi icon-map-marker"></span><span>Lluvia de Oro 57, Arboledas, 28869 Manzanillo, Col.</span></li>
          <li><span class="fi icon-phone"></span><span>+52 (314) 333-3202 Oficina</span></li>
          <li><span class="fi icon-phone"></span><span>+52 (314) 376-9162 Celular</span></li>
          <li><span class="fi icon-envelope"></span><span>inmobiliariaochoa@hotmail.com</span></li>
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
