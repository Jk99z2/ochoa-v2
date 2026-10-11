<div class="topbar">
  <div class="wrap">
    @if ($siteConfig->facebook_url)<a href="{{ $siteConfig->facebook_url }}" target="_blank"><svg class="icon-facebook" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12.06C22 6.505 17.523 2 12 2S2 6.505 2 12.06c0 5.02 3.657 9.184 8.438 9.94v-7.03H7.898v-2.91h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.242 0-1.63.771-1.63 1.562v1.878h2.773l-.443 2.91h-2.33V22c4.78-.756 8.437-4.92 8.437-9.94Z"/></svg> Facebook</a>@endif
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
