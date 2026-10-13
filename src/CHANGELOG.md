# Historial de versiones

Todos los cambios importantes de Ochoa Real Estate Services se registran aquí, y se muestran en el panel de administración en **Versiones**.
El formato sigue [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/) y el proyecto usa [versionado semántico](https://semver.org/lang/es/).

Las versiones anteriores a la 0.8.0 se reconstruyeron a partir del historial de git.

## [Unreleased]

## [0.13.0] - 2026-10-12

### Agregado
- Ícono para "Agregar a pantalla de inicio" en iOS (`apple-touch-icon`), generado a partir del logo del sitio.

### Corregido
- `http://` y `www.` ahora redirigen (301) a la versión canónica `https://` sin `www`, en vez de servirse como copias independientes con código 200. Detectado por una auditoría SEO externa como error crítico: dividía las señales de posicionamiento entre varias versiones de la misma página.
- Varias palabras sin acento en el sitio público: "Operación", "Precio máximo", "aquí", "teléfono", "Recámaras", "Baños", "Descripción", "Ubicación", "Información", "Contáctanos", "construcción", "Página" (en la paginación del carrusel).

## [0.12.1] - 2026-10-12

### Corregido
- Jerarquía de encabezados: el pie de página saltaba de `h2` a `h4` sin pasar por `h3` (aparecía en toda página pública, ya que el pie se comparte), y el listado de propiedades saltaba de `h1` a `h3` directamente. Detectado con el SEO Checker de Yoast corrido contra el sitio en producción. Es un cambio solo de etiqueta HTML, sin efecto visual.

## [0.12.0] - 2026-10-11

### Agregado
- Migas de pan (breadcrumbs) visibles en el listado y en la ficha de cada propiedad, con su versión en datos estructurados (`BreadcrumbList`) generada a partir del mismo arreglo que la versión visible, para que nunca queden desincronizadas.
- Datos estructurados por propiedad (`RealEstateListing`) en cada ficha: precio, operación (venta/renta), disponibilidad, recámaras, baños, superficie de construcción, imágenes y ubicación. Respeta "Ocultar dirección": si está activo, no se incluyen las coordenadas exactas, igual que en la página visible.
- Datos estructurados (`ItemList`) en el listado de propiedades, con solo las propiedades que se ven en esa página.

## [0.11.0] - 2026-10-10

### Agregado
- Datos estructurados (JSON-LD, `RealEstateAgent` de schema.org) en todo el sitio público, para SEO local: nombre, teléfono, correo, dirección y coordenadas de la oficina, y enlaces a redes sociales. Solo incluye los campos que de verdad estén llenos en Configuración.
- Nuevos campos **Latitud** y **Longitud** en Admin → Configuración → "Ubicación de la oficina", usados por esos datos estructurados.

Pendiente: cargar la dirección, teléfono, correo y coordenadas reales de la oficina en Configuración para que los datos estructurados queden completos en producción.

## [0.10.1] - 2026-10-10

### Corregido
- Los íconos del pie de página (ubicación, teléfono, correo, horario) no se mostraban: las clases que usaba la plantilla no existían en `flaticon.css`. Ahora son SVG en línea, igual que el ícono de Facebook.

## [0.10.0] - 2026-10-10

### Quitado
- Se eliminaron del sitio público cerca de 3.75 MB de CSS/JS/fuentes que nunca se usaban: Bootstrap (cargado desde un CDN externo), Animate.css, Owl Carousel, Magnific Popup, AOS, bootstrap-datepicker, tres copias de jQuery y sus plugins, y las fuentes de iconos icomoon/ionicons/open-iconic. Ninguna de esas librerías estaba enlazada desde ninguna vista: el carrusel de inicio, el slider de propiedades y la galería de la ficha ya estaban hechos en JavaScript propio, sin jQuery.
- El ícono de Facebook (la única razón por la que se cargaba la fuente de iconos icomoon, de 1.8 MB) ahora es un SVG en línea.

### Agregado
- `rel="preconnect"` hacia Google Fonts, para que esa fuente cargue un poco más rápido.

## [0.9.0] - 2026-10-10

### Agregado
- Etiqueta `<link rel="canonical">` en inicio, listado y ficha de propiedad, para que los buscadores sepan cuál es la URL "oficial" de cada página. En el listado, la canónica solo conserva los filtros que de verdad cambian los resultados (tipo, operación, precio, municipio, página) e ignora cualquier otro parámetro (por ejemplo, de campañas publicitarias). En la ficha de propiedad, ignora el parámetro `agente` usado para el enlace de referido.
- El listado de propiedades agrega "Página N" al título cuando se navega más allá de la página 1, para que cada página pagina se vea distinta ante los buscadores.

### Seguridad
- El panel de administración (`/admin`) ya no es indexable por buscadores: se agregó `Disallow: /admin` en `robots.txt` y un encabezado `X-Robots-Tag: noindex, nofollow` en todas sus páginas, incluyendo el login.

### Corregido
- La página de inicio podía generar más de un `<h1>` cuando había más de una propiedad destacada, porque el título rotaba dentro del carrusel. Ahora el carrusel usa `<h2>` por propiedad, y la página tiene un único `<h1>` (tomado de "Configuración del sitio" si está definido, o un texto por defecto).

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
