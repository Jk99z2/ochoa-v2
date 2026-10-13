<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Found via an SEO audit against production: www. served the site directly
 * (200) alongside the bare domain, with no redirect consolidating them onto
 * one canonical host - flagged as an error (split ranking/backlink signal,
 * risk of duplicate-content indexing).
 *
 * This does NOT also force https: see the class docblock on
 * RedirectToCanonicalHost - an earlier version did, and caused a live
 * infinite-redirect outage because X-Forwarded-Proto isn't reaching Laravel
 * correctly through this app's proxy chain.
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

    public function test_already_canonical_https_requests_are_never_redirected(): void
    {
        // The exact scenario that caused the outage: an already-correct
        // HTTPS request on the bare domain must never get redirected again.
        config(['app.url' => 'https://example.com']);

        $response = $this->get('https://example.com/propiedades');

        $response->assertOk();
    }

    public function test_plain_http_on_the_canonical_host_is_not_redirected(): void
    {
        // Deliberate: scheme is left to the edge (Cloudflare), not here.
        config(['app.url' => 'https://example.com']);

        $response = $this->get('http://example.com/propiedades');

        $response->assertOk();
    }

    public function test_local_dev_is_unaffected(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
