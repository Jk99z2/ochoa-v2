<div class="topbar">
  <div class="wrap">
    @if ($siteConfig->facebook_url)<a href="{{ $siteConfig->facebook_url }}" target="_blank"><span class="icon-facebook"></span> Facebook</a>@endif
    <div class="phones">
      <span class="phones-label">Contacto:</span>
      <a href="tel:{{ preg_replace("/[^0-9+]/", "", $siteConfig->telefono_oficina) }}">{{ $siteConfig->telefono_oficina }}</a>
      <a href="tel:{{ preg_replace("/[^0-9+]/", "", $siteConfig->telefono_celular) }}">{{ $siteConfig->telefono_celular }}</a>
    </div>
  </div>
</div>

<nav class="nav">
  <div class="wrap">
    <a href="/" class="nav-brand">
      <img src="{{ $siteConfig->logo_path ? Storage::url($siteConfig->logo_path) : "/logos/logochoa.png" }}" alt="{{ $siteConfig->nombre_sitio }}">
      <div class="nav-brand-text">{{ $siteConfig->nombre_sitio }}</div>
    </a>
    <button class="nav-burger" id="burger">Menu</button>
    <ul class="nav-menu" id="nav-menu">
      <li><a href="/" @class(["active" => request()->is("/")])>Inicio</a></li>
      <li class="nav-drop">
        <a href="{{ route("propiedades.index") }}" @class(["active" => request()->routeIs("propiedades.*")])>Propiedades</a>
        <div class="nav-drop-panel">
          <a href="{{ route("propiedades.index") }}">Todas</a>
          @foreach ($navTipos as $navTipo)
            <a href="{{ route("propiedades.index", ["tipo" => $navTipo->slug]) }}">{{ $navTipo->nombre }}</a>
          @endforeach
        </div>
      </li>
      <li><a href="#contacto">Contacto</a></li>
    </ul>
  </div>
</nav>
