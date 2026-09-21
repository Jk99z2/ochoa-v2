Nuevo lead desde el sitio web

@if ($lead->propiedad)
Propiedad: {!! $lead->propiedad->titulo !!}@if ($lead->propiedad->clave) ({{ $lead->propiedad->clave }})@endif

@endif
Nombre: {!! $lead->nombre !!}
@if ($lead->email)
Email: {!! $lead->email !!}
@endif
@if ($lead->telefono)
Teléfono: {!! $lead->telefono !!}
@endif
@if ($lead->mensaje)

Mensaje:
{!! $lead->mensaje !!}
@endif

Ver en el panel: {!! $adminUrl !!}
