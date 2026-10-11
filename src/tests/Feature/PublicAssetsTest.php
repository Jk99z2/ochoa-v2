<?php

namespace Tests\Feature;

use App\Models\Configuracion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Locks in the dead-weight cleanup: the public pages used to load ~1MB of
 * vendored CSS/JS (Bootstrap, animate.css, owl-carousel, AOS, magnific-popup,
 * icomoon, ionicons, open-iconic) that nothing in the markup actually used.
 */
class PublicAssetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_removed_vendor_assets_are_never_referenced(): void
    {
        $dead = [
            'bootstrap@5.0.2', 'animate.css', 'owl.carousel', 'owl.theme',
            'magnific-popup.css', '/css/aos.css', 'ionicons.min.css',
            'open-iconic', '/css/icomoon.css', '/css/bootstrap-datepicker.css',
            '/css/bootstrap.min.css', 'ajax-loader.gif',
        ];

        foreach (['/', '/propiedades'] as $url) {
            $html = $this->get($url)->getContent();

            foreach ($dead as $needle) {
                $this->assertStringNotContainsString($needle, $html, "{$url} still references {$needle}");
            }
        }
    }

    public function test_still_loads_the_stylesheets_the_site_actually_uses(): void
    {
        $response = $this->get('/');

        $response->assertSee('<link rel="stylesheet" href="/css/flaticon.css">', false);
        $response->assertSee('<link rel="stylesheet" href="/css/site.css">', false);
        $response->assertSee('<link rel="stylesheet" href="/css/style.css">', false);
    }

    public function test_facebook_icon_renders_as_inline_svg_not_an_icon_font_glyph(): void
    {
        Configuracion::actual()->update(['facebook_url' => 'https://facebook.com/ochoa']);

        $response = $this->get('/');

        $response->assertSee('<svg class="icon-facebook"', false);
    }

    public function test_footer_contact_icons_render_as_inline_svgs_not_dead_icon_font_classes(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();

        // Previously icon-map-marker/icon-phone/icon-envelope/icon-calendar, none of
        // which exist in flaticon.css, so nothing ever rendered for these four icons.
        $this->assertStringNotContainsString('icon-map-marker', $html);
        $this->assertStringNotContainsString('icon-phone', $html);
        $this->assertStringNotContainsString('icon-envelope', $html);
        $this->assertStringNotContainsString('icon-calendar', $html);

        // 5 icons total: pin, phone (oficina), phone (celular), envelope, calendar.
        $this->assertSame(5, substr_count($html, '<span class="fi"><svg'));
    }
}
