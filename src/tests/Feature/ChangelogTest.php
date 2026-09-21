<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\Changelog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangelogTest extends TestCase
{
    use RefreshDatabase;

    private const SAMPLE = <<<'MD'
# Historial

Texto de introducción que se ignora.

## [Unreleased]

### Agregado
- Algo en camino

## [1.1.0] - 2026-09-21

### Agregado
- Primera entrada con `codigo`
  que continúa en otra línea
- Segunda entrada

### Corregido
- Un error

## [1.0.0] - 2026-08-01

### Agregado
- Lanzamiento inicial

[1.1.0]: https://example.com/compare/1.0.0...1.1.0
MD;

    public function test_parses_releases_newest_first_with_sections_and_entries(): void
    {
        $releases = Changelog::parse(self::SAMPLE);

        $this->assertSame(['Unreleased', '1.1.0', '1.0.0'], array_column($releases, 'version'));
        $this->assertTrue($releases[0]['unreleased']);
        $this->assertNull($releases[0]['date']);
        $this->assertSame('2026-09-21', $releases[1]['date']->toDateString());
        $this->assertSame(['Agregado', 'Corregido'], array_keys($releases[1]['sections']));
        $this->assertSame(
            ['Primera entrada con `codigo` que continúa en otra línea', 'Segunda entrada'],
            $releases[1]['sections']['Agregado'],
        );
        $this->assertSame(['Un error'], $releases[1]['sections']['Corregido']);
    }

    public function test_link_reference_lines_are_not_treated_as_entries(): void
    {
        $releases = Changelog::parse(self::SAMPLE);

        $this->assertSame(['Lanzamiento inicial'], $releases[2]['sections']['Agregado']);
    }

    public function test_current_skips_unreleased(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'changelog');
        file_put_contents($path, self::SAMPLE);

        $this->assertSame('1.1.0', Changelog::current($path));

        unlink($path);
    }

    public function test_missing_file_yields_no_releases_and_no_version(): void
    {
        $this->assertSame([], Changelog::releases('/nonexistent/CHANGELOG.md'));
        $this->assertNull(Changelog::current('/nonexistent/CHANGELOG.md'));
    }

    public function test_inline_escapes_html_but_keeps_code_spans(): void
    {
        $html = (string) Changelog::inline('Usa `php artisan` <script>alert(1)</script>');

        $this->assertSame('Usa <code>php artisan</code> &lt;script&gt;alert(1)&lt;/script&gt;', $html);
    }

    public function test_the_real_changelog_has_a_semver_current_version(): void
    {
        $this->assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', (string) Changelog::current());
    }

    public function test_versiones_page_shows_the_timeline_to_any_panel_user(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/versiones');

        $response->assertOk();
        $response->assertSee('Historial de versiones');
        $response->assertSee('Versión '.Changelog::current());
        $response->assertSee('Versión actual');
    }

    public function test_versiones_page_requires_login(): void
    {
        $this->get('/admin/versiones')->assertRedirect();
    }

    public function test_panel_footer_shows_the_current_version(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertSee('Ochoa Real Estate v'.Changelog::current());
    }
}
