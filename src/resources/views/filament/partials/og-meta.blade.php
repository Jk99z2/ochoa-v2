@php
    $ogTitle = filament()->getBrandName() . ' - Panel de administración';
    $ogDescription = 'Accede al panel de administración de Ochoa Real Estate Services.';
    $ogImage = asset('logos/logochoa.png');
@endphp

<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ filament()->getBrandName() }}">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
