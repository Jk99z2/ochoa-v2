<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Found via an SEO audit against production: http:// and www. both served
 * the site directly (200), with no redirect consolidating them onto one
 * canonical host+scheme - flagged as an error (split ranking/backlink
 * signal, risk of duplicate-content indexing).
 */
class RedirectToCanonicalHostTest extends TestCase
{
    use RefreshDatabase;

    public function test_www_redirects_to_the_canonical_non_www_host(): void
    {
        config(['app.url' => 'https://example.com']);

        $response = $this->get('https://www.example.com/propiedades?tipo=casa');

        $response->assertRedirect('https://example.com/propiedades?tipo=casa');
        $response->assertStatus(301);
    }

    public function test_http_redirects_to_https_when_canonical_is_https(): void
    {
        config(['app.url' => 'https://example.com']);

        $response = $this->get('http://example.com/propiedades');

        $response->assertRedirect('https://example.com/propiedades');
        $response->assertStatus(301);
    }

    public function test_www_and_http_together_redirect_once_not_in_a_chain(): void
    {
        config(['app.url' => 'https://example.com']);

        $response = $this->get('http://www.example.com/');

        // A single hop straight to the fully canonical URL, not two redirects.
        $response->assertRedirect('https://example.com/');
        $response->assertStatus(301);
    }

    public function test_already_canonical_requests_pass_through_unredirected(): void
    {
        config(['app.url' => 'https://example.com']);

        $response = $this->get('https://example.com/propiedades');

        $response->assertOk();
    }

    public function test_local_dev_is_unaffected(): void
    {
        // Default local/testing APP_URL has no www counterpart and the
        // scheme already matches, so there's nothing to redirect.
        $response = $this->get('/');

        $response->assertOk();
    }
}
