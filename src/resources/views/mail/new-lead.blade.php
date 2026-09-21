<!DOCTYPE html>
<html lang="es">
<body style="font-family: Arial, Helvetica, sans-serif; color: #222; line-height: 1.5;">
  <h2 style="margin: 0 0 12px;">Nuevo lead desde el sitio web</h2>

  @if ($lead->propiedad)
    <p style="margin: 0 0 12px;">
      <strong>Propiedad:</strong>
      <a href="{{ url('/propiedades/'.$lead->propiedad->slug) }}">{{ $lead->propiedad->titulo }}</a>
      @if ($lead->propiedad->clave)
        ({{ $lead->propiedad->clave }})
      @endif
    </p>
  @endif

  <table cellpadding="4" cellspacing="0" style="border-collapse: collapse;">
    <tr><td><strong>Nombre:</strong></td><td>{{ $lead->nombre }}</td></tr>
    @if ($lead->email)
      <tr><td><strong>Email:</strong></td><td><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td></tr>
    @endif
    @if ($lead->telefono)
      <tr><td><strong>Teléfono:</strong></td><td>{{ $lead->telefono }}</td></tr>
    @endif
  </table>

  @if ($lead->mensaje)
    <p style="margin: 16px 0 4px;"><strong>Mensaje:</strong></p>
    <p style="margin: 0; padding: 8px 12px; background: #f5f5f5; white-space: pre-line;">{{ $lead->mensaje }}</p>
  @endif

  <p style="margin-top: 20px;">
    <a href="{{ $adminUrl }}">Ver en el panel de administración</a>
  </p>
</body>
</html>
