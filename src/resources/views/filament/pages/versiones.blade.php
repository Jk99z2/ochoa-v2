<x-filament-panels::page>
    {{-- Filament's compiled CSS only ships the utilities its own views use, so the timeline is styled here. --}}
    <style>
        .versiones-timeline { position: relative; margin-left: .5rem; padding-left: 1.75rem; border-left: 2px solid rgba(128, 128, 128, .3); }
        .versiones-item { position: relative; margin-bottom: 1.5rem; }
        .versiones-item:last-child { margin-bottom: 0; }
        .versiones-dot { position: absolute; left: -2.2rem; top: 1.1rem; width: .75rem; height: .75rem; border-radius: 9999px; background: rgb(156, 163, 175); border: 2px solid transparent; }
        .versiones-item.is-current .versiones-dot { background: rgb(var(--primary-500)); }
        .versiones-item.is-unreleased .versiones-dot { background: transparent; border-color: rgb(156, 163, 175); }
        .versiones-badge { margin-bottom: .75rem; }
        .versiones-section-title { margin: 1rem 0 .35rem; font-size: .8rem; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; opacity: .7; }
        .versiones-section-title:first-of-type { margin-top: 0; }
        .versiones-list { margin: 0; padding-left: 1.1rem; list-style: disc; }
        .versiones-list li { margin-bottom: .25rem; font-size: .9rem; line-height: 1.45; }
        .versiones-list code { padding: 0 .3rem; border-radius: .25rem; background: rgba(128, 128, 128, .18); font-size: .85em; }
    </style>

    @if ($releases === [])
        <x-filament::section>
            Todavía no hay versiones registradas en <code>CHANGELOG.md</code>.
        </x-filament::section>
    @else
        <div class="versiones-timeline">
            @foreach ($releases as $release)
                <div @class([
                    'versiones-item',
                    'is-current' => ! $release['unreleased'] && $release['version'] === $current,
                    'is-unreleased' => $release['unreleased'],
                ])>
                    <span class="versiones-dot"></span>

                    <x-filament::section
                        :heading="$release['unreleased'] ? 'Próximamente' : 'Versión ' . $release['version']"
                        :description="$release['date']?->locale('es')->translatedFormat('j \d\e F \d\e Y')"
                    >
                        @if ($release['unreleased'])
                            <x-filament::badge class="versiones-badge" color="gray">Sin publicar</x-filament::badge>
                        @elseif ($release['version'] === $current)
                            <x-filament::badge class="versiones-badge" color="success">Versión actual</x-filament::badge>
                        @endif

                        @foreach ($release['sections'] as $title => $items)
                            @continue($items === [])

                            <h4 class="versiones-section-title">{{ $title }}</h4>
                            <ul class="versiones-list">
                                @foreach ($items as $item)
                                    <li>{{ \App\Support\Changelog::inline($item) }}</li>
                                @endforeach
                            </ul>
                        @endforeach
                    </x-filament::section>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
