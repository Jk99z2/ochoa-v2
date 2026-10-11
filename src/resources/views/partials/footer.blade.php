@php
  // Inline SVGs: flaticon.css only defines pin/detective/house/purse/bed/bathtub/selection,
  // none of which cover these four icons, so the icon-map-marker/icon-phone/icon-envelope/
  // icon-calendar classes previously here never matched a glyph and rendered nothing.
  $iconSvg = fn (string $path) => '<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">'.$path.'</svg>';
  $iconPin = $iconSvg('<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/>');
  $iconPhone = $iconSvg('<path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.61 21 3 13.39 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.46.57 3.58a1 1 0 0 1-.25 1.01l-2.2 2.2Z"/>');
  $iconEnvelope = $iconSvg('<path d="M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5Zm2.2.2 6.8 5.44 6.8-5.44H5.2ZM19 7.37l-6.38 5.1a1 1 0 0 1-1.24 0L5 7.37V19h14V7.37Z"/>');
  $iconCalendar = $iconSvg('<path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1Zm13 8H5v10h15V10Z"/>');
@endphp
<footer class="footer" id="contacto">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <h4>{{ $siteConfig->nombre_sitio }}</h4>
        <p>Gran variedad de bienes inmuebles en el Puerto de Manzanillo. Invertir en este hermoso lugar es siempre un acierto.</p>
        <div class="footer-social">
          @if ($siteConfig->facebook_url)<a href="{{ $siteConfig->facebook_url }}" target="_blank"><svg class="icon-facebook" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12.06C22 6.505 17.523 2 12 2S2 6.505 2 12.06c0 5.02 3.657 9.184 8.438 9.94v-7.03H7.898v-2.91h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.242 0-1.63.771-1.63 1.562v1.878h2.773l-.443 2.91h-2.33V22c4.78-.756 8.437-4.92 8.437-9.94Z"/></svg></a>@endif
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
          <li><span class="fi">{!! $iconPin !!}</span><span>{{ $siteConfig->direccion }}</span></li>
          <li><span class="fi">{!! $iconPhone !!}</span><a href="tel:{{ preg_replace("/[^0-9+]/", "", $siteConfig->telefono_oficina) }}">{{ $siteConfig->telefono_oficina }} Oficina</a></li>
          <li><span class="fi">{!! $iconPhone !!}</span><a href="tel:{{ preg_replace("/[^0-9+]/", "", $siteConfig->telefono_celular) }}">{{ $siteConfig->telefono_celular }} Celular</a></li>
          <li><span class="fi">{!! $iconEnvelope !!}</span><a href="mailto:{{ $siteConfig->email_contacto }}">{{ $siteConfig->email_contacto }}</a></li>
          <li><span class="fi">{!! $iconCalendar !!}</span><span>{{ $siteConfig->horario }}</span></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="wrap">
      Todos los Derechos Reservados &copy; {{ date("Y") }} - {{ $siteConfig->nombre_sitio }}
    </div>
  </div>
</footer>
