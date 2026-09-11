<footer class="footer" id="contacto">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <h4>{{ $siteConfig->nombre_sitio }}</h4>
        <p>Gran variedad de bienes inmuebles en el Puerto de Manzanillo. Invertir en este hermoso lugar es siempre un acierto.</p>
        <div class="footer-social">
          @if ($siteConfig->facebook_url)<a href="{{ $siteConfig->facebook_url }}" target="_blank"><span class="icon-facebook"></span></a>@endif
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
          <li><span class="fi icon-map-marker"></span><span>{{ $siteConfig->direccion }}</span></li>
          <li><span class="fi icon-phone"></span><a href="tel:{{ preg_replace("/[^0-9+]/", "", $siteConfig->telefono_oficina) }}">{{ $siteConfig->telefono_oficina }} Oficina</a></li>
          <li><span class="fi icon-phone"></span><a href="tel:{{ preg_replace("/[^0-9+]/", "", $siteConfig->telefono_celular) }}">{{ $siteConfig->telefono_celular }} Celular</a></li>
          <li><span class="fi icon-envelope"></span><a href="mailto:{{ $siteConfig->email_contacto }}">{{ $siteConfig->email_contacto }}</a></li>
          <li><span class="fi icon-calendar"></span><span>{{ $siteConfig->horario }}</span></li>
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
