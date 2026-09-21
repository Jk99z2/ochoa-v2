# Historial de versiones

Todos los cambios importantes de Ochoa Real Estate Services se registran aquí, y se muestran en el panel de administración en **Versiones**.
El formato sigue [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/) y el proyecto usa [versionado semántico](https://semver.org/lang/es/).

Las versiones anteriores a la 0.8.0 se reconstruyeron a partir del historial de git.

## [0.8.0] - 2026-09-21

### Agregado
- Correo de aviso cuando llega un lead nuevo desde el formulario del sitio: le llega al agente asignado, o al agente de la propiedad, o al correo de contacto de la oficina. Al responder el correo, la respuesta va directo al cliente.
- Página **Versiones** en el panel con el historial de cambios, y la versión actual en el pie del panel.

### Cambiado
- El mensaje del formulario de contacto ahora tiene un máximo de 2000 caracteres.

## [0.7.1] - 2026-09-15

### Seguridad
- Se actualizó `league/commonmark` para corregir 4 avisos de seguridad de severidad alta.

## [0.7.0] - 2026-09-14

### Agregado
- `sitemap.xml` dinámico y `robots.txt` para buscadores.
- Etiquetas Open Graph y Twitter en el panel de administración para que los enlaces se vean bien al compartirse.
- Miniaturas de las imágenes de las propiedades para que el listado cargue más rápido (las imágenes existentes se procesan con `imagenes:backfill-thumbnails`).
- Pruebas automáticas de la página de inicio y del listado de propiedades.

### Cambiado
- Las vistas públicas ahora comparten un mismo diseño base y un componente de tarjeta de propiedad.

## [0.6.0] - 2026-09-12

### Agregado
- Registro de inicios de sesión, con historial visible por usuario en su perfil.
- Paginación con puntos en los carruseles de propiedades nuevas y recomendadas.
- Ícono de calendario en el horario del pie de página.

### Cambiado
- Los estilos de las vistas públicas se movieron a hojas de estilo externas.

## [0.5.0] - 2026-09-09

### Agregado
- Municipios con clave propia y código de referencia generado automáticamente para cada propiedad (por ejemplo `MZO-0001`).
- Widgets en el panel: resumen de cifras, últimos leads y propiedades más vistas.
- Scripts de despliegue para producción y staging, y pruebas automáticas en GitHub Actions.

### Cambiado
- Nuevo diseño de la página de inicio.
- La gestión de agentes ahora es solo para administradores.
- El formulario de contacto se movió a su propio controlador, con pruebas.
- Se pueden eliminar agentes sin borrar sus propiedades.

### Corregido
- Error al elegir municipio en una propiedad.
- Falla de la migración de municipios en producción (MySQL error 1832).

## [0.4.0] - 2026-09-08

### Agregado
- Gestión de usuarios, enlaces de referido por agente y atribución de leads.
- Los agentes solo ven sus propios leads; la ficha de la propiedad muestra el contacto del agente que refirió.
- Configuración del sitio (nombre, logo, contacto, portada) editable desde el panel.
- Filtro básico contra spam en los formularios (enlaces y palabras clave).

### Cambiado
- Nueva tipografía Inter en todo el sitio.

## [0.3.0] - 2026-09-06

### Agregado
- Configuración de producción: acceso al panel, confianza en el proxy y `docker-compose.prod.yml`.
- Script para refrescar staging con los datos e imágenes de producción.
- README con guía de instalación, flujo de trabajo y despliegue.

### Corregido
- El servidor web de producción y el de staging podían mezclarse al compartir red; ahora cada uno usa su propio contenedor.

## [0.2.0] - 2026-08-31

### Agregado
- Página pública de listado de propiedades.
- Formularios de contacto con protección contra bots.
- Identidad visual del panel de administración.

## [0.1.0] - 2026-08-06

### Agregado
- Panel de administración con Filament.
- Base de datos y recursos para propiedades, agentes, leads, tipos y amenidades.
