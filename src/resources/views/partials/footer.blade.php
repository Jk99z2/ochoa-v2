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
