<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Illuminate\Support\HtmlString;

/**
 * Reads CHANGELOG.md (Keep a Changelog layout) into a list of releases, newest first:
 *
 *   ## [1.2.0] - 2026-09-21      a release ("## [Unreleased]" is also accepted)
 *   ### Agregado                 a section inside the release
 *   - Something that changed     an entry (indented lines continue the entry)
 */
class Changelog
{
    /**
     * @return list<array{version: string, date: ?CarbonImmutable, unreleased: bool, sections: array<string, list<string>>}>
     */
    public static function releases(?string $path = null): array
    {
        $path ??= base_path('CHANGELOG.md');

        return is_file($path) ? static::parse((string) file_get_contents($path)) : [];
    }

    /**
     * The newest released version, or null if there is none yet.
     */
    public static function current(?string $path = null): ?string
    {
        foreach (static::releases($path) as $release) {
            if (! $release['unreleased']) {
                return $release['version'];
            }
        }

        return null;
    }

    /**
     * @return list<array{version: string, date: ?CarbonImmutable, unreleased: bool, sections: array<string, list<string>>}>
     */
    public static function parse(string $markdown): array
    {
        $releases = [];
        $section = null;

        foreach (preg_split('/\R/', $markdown) as $line) {
            if (preg_match('/^##\s+\[([^\]]+)\](?:\s+-\s+(\d{4}-\d{2}-\d{2}))?/', $line, $m)) {
                $releases[] = [
                    'version' => $m[1],
                    'date' => isset($m[2]) ? CarbonImmutable::parse($m[2]) : null,
                    'unreleased' => strcasecmp($m[1], 'Unreleased') === 0,
                    'sections' => [],
                ];
                $section = null;

                continue;
            }

            if ($releases === []) {
                continue;
            }

            $current = array_key_last($releases);

            if (preg_match('/^###\s+(.+?)\s*$/', $line, $m)) {
                $section = $m[1];
                $releases[$current]['sections'][$section] ??= [];
            } elseif ($section !== null && preg_match('/^[-*]\s+(.+?)\s*$/', $line, $m)) {
                $releases[$current]['sections'][$section][] = $m[1];
            } elseif ($section !== null && preg_match('/^\s+(\S.*?)\s*$/', $line, $m) && $releases[$current]['sections'][$section] !== []) {
                $last = array_key_last($releases[$current]['sections'][$section]);
                $releases[$current]['sections'][$section][$last] .= ' '.$m[1];
            }
        }

        return $releases;
    }

    /**
     * Escape an entry for HTML, keeping only `code` spans as markup.
     */
    public static function inline(string $text): HtmlString
    {
        return new HtmlString(preg_replace('/`([^`]+)`/', '<code>$1</code>', e($text)));
    }
}
