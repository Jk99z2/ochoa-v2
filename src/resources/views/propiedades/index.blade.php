<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Propiedades en Manzanillo, Colima - Ochoa Real Estate Services</title>
  <meta name="description" content="Explora casas, terrenos y departamentos en venta y renta en Manzanillo, Colima. Encuentra tu proxima propiedad con Ochoa Real Estate Services.">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
  <link rel="stylesheet" href="/css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="/css/animate.css">
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
      <li class="nav-drop">
        <a href="{{ route("propiedades.index") }}" class="active">Propiedades</a>
        <div class="nav-drop-panel">
          <a href="{{ route("propiedades.index") }}">Todas</a>
          @foreach ($navTipos as $navTipo)
            <a href="{{ route("propiedades.index", ["tipo" => $navTipo->slug]) }}">{{ $navTipo->nombre }}</a>
          @endforeach
        </div>
      </li>
      <li><a href="/#contacto">Contacto</a></li>
    </ul>
  </div>
</nav>

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
          <label>Ciudad</label>
          <input type="text" name="ciudad" value="{{ request("ciudad") }}" placeholder="Manzanillo">
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
        @if (request()->anyFilled(["tipo", "operacion", "ciudad", "min_price", "max_price"]))
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
              </div>
              <div class="prop-body">
                <h3><a href="{{ route("propiedades.show", $p->slug) }}">{{ $p->titulo }}</a></h3>
                <p class="prop-cat">{{ $p->tipo?->nombre }} - {{ $p->ciudad }}</p>
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

        <div class="listing-pagination">
          {{ $propiedades->links() }}
        </div>
      @endif
    </div>
  </div>
</section>

<style>
:root { --ink:#111010; --gold:#b8872a; --gold-lt:#d4a84b; --cream:#f5f1ea; --warm:#ede8de; --muted:#7a7468; --white:#ffffff; --border:rgba(184,135,42,.18); --ease:cubic-bezier(.4,0,.2,1); }
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:"DM Sans",sans-serif;background:var(--white);color:var(--ink);overflow-x:hidden}
a{text-decoration:none;color:inherit} img{display:block;max-width:100%} ul{list-style:none}
.topbar{background:var(--ink);height:36px;display:flex;align-items:center;font-size:11.5px;color:rgba(255,255,255,.45)}
.wrap{max-width:1260px;margin:0 auto;padding:0 24px;width:100%}
.topbar .wrap{display:flex;justify-content:space-between;align-items:center}
.topbar a{color:rgba(255,255,255,.4)} .topbar a:hover{color:var(--gold-lt)}
.topbar .phones{display:flex;gap:20px} .topbar .phones span{display:flex;align-items:center;gap:5px}
@media (max-width:600px){.topbar .phones span:last-child{display:none}}
.nav{position:sticky;top:0;z-index:900;height:64px;background:rgba(17,16,16,.97);backdrop-filter:blur(12px);border-bottom:1px solid var(--border)}
.nav .wrap{height:100%;display:flex;justify-content:space-between;align-items:center;position:relative}
.nav-brand{display:flex;align-items:center;gap:10px;flex-shrink:0} .nav-brand img{height:32px;width:auto}
.nav-brand-text{font-family:"Cormorant Garamond",serif;font-size:17px;font-weight:600;color:#fff;line-height:1.2}
.nav-brand-text em{color:var(--gold);font-style:normal}
.nav-menu{display:flex;align-items:center;gap:2px}
.nav-menu li a{display:block;padding:6px 12px;font-size:12.5px;color:rgba(255,255,255,.6);border-radius:3px;transition:color .2s,background .2s;white-space:nowrap}
.nav-menu li a:hover{color:#fff;background:rgba(255,255,255,.07)} .nav-menu li a.active{color:var(--gold-lt)}
.nav-drop{position:relative}
.nav-drop-panel{display:none;position:absolute;top:100%;left:0;background:#1a1918;border:1px solid var(--border);border-radius:4px;min-width:190px;padding:6px 0;box-shadow:0 16px 40px rgba(0,0,0,.4);z-index:10}
.nav-drop:hover .nav-drop-panel{display:block}
.nav-drop-panel a{display:block;padding:8px 18px;font-size:12.5px;color:rgba(255,255,255,.6)!important;border-radius:0!important;background:none!important}
.nav-drop-panel a:hover{background:rgba(184,135,42,.12)!important;color:#fff!important}
.nav-burger{display:none;background:none;border:1px solid rgba(255,255,255,.2);border-radius:3px;padding:6px 10px;cursor:pointer;color:#fff;font-size:14px}
@media (max-width:900px){
  .nav-burger{display:block}
  .nav-menu{display:none;flex-direction:column;align-items:flex-start;position:absolute;top:64px;left:0;right:0;background:#141312;border-top:1px solid var(--border);padding:10px 16px 20px}
  .nav-menu.open{display:flex} .nav-menu li{width:100%} .nav-menu li a{padding:10px 8px;font-size:14px}
  .nav-drop-panel{position:static;border:none;box-shadow:none;background:rgba(255,255,255,.04);padding:0 0 0 14px;display:block;min-width:auto}
}
.listing-hero{background:var(--cream);padding:48px 0 32px}
.listing-title{font-family:"Cormorant Garamond",serif;font-size:clamp(28px,3.5vw,42px);font-weight:700;margin-bottom:6px}
.listing-count{font-size:13.5px;color:var(--muted)}
.listing-body{padding:48px 0 80px}
.listing-grid{display:grid;grid-template-columns:260px 1fr;gap:40px;align-items:start}
@media (max-width:820px){.listing-grid{grid-template-columns:1fr}}
.filters{background:var(--cream);border-radius:6px;padding:24px;position:sticky;top:88px}
.filters-title{font-family:"Cormorant Garamond",serif;font-size:18px;font-weight:600;margin-bottom:18px}
.filter-group{margin-bottom:16px}
.filter-group label{display:block;font-size:11.5px;font-weight:500;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:6px}
.filter-group select,.filter-group input{width:100%;padding:9px 10px;border:1px solid rgba(0,0,0,.12);border-radius:4px;font-size:13.5px;font-family:inherit;background:var(--white);color:var(--ink)}
.filter-submit{width:100%;background:var(--gold);color:#fff;border:none;padding:11px;border-radius:4px;font-size:12px;font-weight:500;letter-spacing:.5px;text-transform:uppercase;cursor:pointer;transition:background .2s}
.filter-submit:hover{background:var(--gold-lt)}
.filter-clear{display:block;text-align:center;margin-top:10px;font-size:12.5px;color:var(--muted);text-decoration:underline}
.no-results{text-align:center;padding:60px 20px;color:var(--muted)}
.no-results p{margin-bottom:16px}
.results-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
@media (max-width:1000px){.results-grid{grid-template-columns:repeat(2,1fr)}}
@media (max-width:600px){.results-grid{grid-template-columns:1fr}}
.prop-card{background:var(--white);border:1px solid rgba(0,0,0,.07);overflow:hidden;transition:box-shadow .3s var(--ease),transform .3s var(--ease)}
.prop-card:hover{box-shadow:0 14px 40px rgba(0,0,0,.1);transform:translateY(-4px)}
.prop-img{position:relative;overflow:hidden;height:190px;background:var(--warm)}
.prop-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s var(--ease)}
.prop-card:hover .prop-img img{transform:scale(1.06)}
.prop-badge{position:absolute;top:12px;left:12px;background:var(--gold);color:#fff;font-size:9.5px;font-weight:500;letter-spacing:1px;text-transform:uppercase;padding:4px 10px}
.prop-body{padding:16px}
.prop-body h3{font-family:"Cormorant Garamond",serif;font-size:17px;font-weight:600;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.prop-body h3 a:hover{color:var(--gold)}
.prop-cat{font-size:11.5px;color:var(--muted);margin-bottom:10px}
.prop-price{font-family:"Cormorant Garamond",serif;font-size:19px;font-weight:600;color:var(--gold)}
.prop-meta{display:flex;gap:12px;margin-top:10px;padding-top:10px;border-top:1px solid rgba(0,0,0,.06);font-size:11px;color:var(--muted)}
.listing-pagination{margin-top:36px;display:flex;justify-content:center}
.listing-pagination nav{font-size:13px}
.footer{background:var(--ink);color:rgba(255,255,255,.45);padding:68px 0 0;margin-top:20px}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1.7fr;gap:56px;padding-bottom:56px;border-bottom:1px solid rgba(255,255,255,.07)}
@media (max-width:820px){.footer-grid{grid-template-columns:1fr 1fr;gap:32px}}
@media (max-width:480px){.footer-grid{grid-template-columns:1fr}}
.footer h4{font-family:"Cormorant Garamond",serif;font-size:18px;font-weight:600;color:#fff;margin-bottom:18px}
.footer p{font-size:13px;line-height:1.75}
.footer-links li{margin-bottom:9px}
.footer-links a{font-size:13px;color:rgba(255,255,255,.4)} .footer-links a:hover{color:var(--gold-lt)}
.footer-contact li{display:flex;gap:10px;font-size:13px;line-height:1.65;margin-bottom:11px}
.footer-contact .fi{color:var(--gold);flex-shrink:0;margin-top:2px}
.footer-social{display:flex;gap:10px;margin-top:20px}
.footer-social a{width:34px;height:34px;border:1px solid rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;color:rgba(255,255,255,.4)}
.footer-bottom{padding:18px 0;text-align:center;font-size:11.5px;color:rgba(255,255,255,.18)}
@media (max-width: 820px) {
  .filters { position: static; margin-bottom: 24px; }
  .listing-hero { padding: 32px 0 24px; }
  .filter-group select, .filter-group input { font-size: 16px; }
}
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
          <li><a href="{{ route("propiedades.index") }}">Propiedades</a></li>
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
</script>

</body>
</html>
