@php($version = \App\Support\Changelog::current())

@if ($version)
    <div style="padding: 1rem; text-align: center; font-size: .75rem; opacity: .6;">
        @auth
            <a href="{{ \App\Filament\Pages\Versiones::getUrl() }}" style="text-decoration: underline;">Ochoa Real Estate v{{ $version }}</a>
        @else
            Ochoa Real Estate v{{ $version }}
        @endauth
    </div>
@endif
