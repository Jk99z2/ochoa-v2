<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield("title", "Ochoa Real Estate Services")</title>
  @yield("meta")
  {{-- ?v=mtime: Cloudflare caches /css for 4 h, so a changed file needs a new URL to reach visitors. --}}
  <link rel="stylesheet" href="/css/flaticon.css?v={{ filemtime(public_path("css/flaticon.css")) }}">
  <link rel="stylesheet" href="/css/style.css?v={{ filemtime(public_path("css/style.css")) }}">
  <link rel="icon" href="{{ $siteConfig->favicon_path ? Storage::url($siteConfig->favicon_path) : '/logos/logochoa.png' }}" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="/logos/apple-touch-icon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/site.css?v={{ filemtime(public_path("css/site.css")) }}">
  @stack("styles")
  @include("partials.local-business-schema")
</head>
<body>
@yield("content")
</body>
</html>
