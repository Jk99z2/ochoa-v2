{{--
  $crumbs: ordered list of ['name' => string, 'url' => string|null].
  A null url marks the current page (no link), which must be the last item.
  Google requires the JSON-LD breadcrumb to match what's visibly on the page,
  so both are built from the same array here instead of kept in sync by hand.
--}}
@php
  $breadcrumbSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($crumbs)->values()->map(fn ($crumb, $i) => array_filter([
      '@type' => 'ListItem',
      'position' => $i + 1,
      'name' => $crumb['name'],
      'item' => $crumb['url'] ?? null,
    ]))->all(),
  ];
@endphp
<nav class="breadcrumbs" aria-label="Ruta de navegación">
  <ol>
    @foreach ($crumbs as $crumb)
      <li>
        @if ($crumb['url'] ?? null)
          <a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
        @else
          <span aria-current="page">{{ $crumb['name'] }}</span>
        @endif
      </li>
    @endforeach
  </ol>
</nav>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
