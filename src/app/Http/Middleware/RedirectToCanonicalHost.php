<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirects www. to the bare canonical host (whatever APP_URL declares), so
 * www. never exists as a live, separately-200ing duplicate of the site -
 * split backlink/ranking signal, flagged as an error by SEO crawlers.
 * Cloudflare/Nginx Proxy Manager sit in front of this app and aren't
 * version-controlled here, so this is enforced at the application layer
 * instead, where it's guaranteed to run regardless of their config.
 *
 * Does NOT also force https: that needs $request->isSecure(), which reads
 * X-Forwarded-Proto - and in production, behind Cloudflare + Nginx Proxy
 * Manager, that header isn't reaching Laravel as "https" even on genuine
 * HTTPS requests. An earlier version checked scheme too and caused a live
 * infinite-redirect outage on 2026-10-12: every already-HTTPS request read
 * as insecure and got "corrected" to HTTPS again, forever. The HTTP->HTTPS
 * upgrade belongs at the edge (Cloudflare's "Always Use HTTPS") where it
 * doesn't depend on a forwarded header surviving two proxy hops.
 *
 * Self-gating: in local dev (and tests) APP_URL is http://localhost:8082,
 * so there's no www counterpart - nothing to redirect, no separate
 * environment check needed.
 */
class RedirectToCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $canonical = parse_url((string) config('app.url'));
        $canonicalHost = $canonical['host'] ?? null;
        $canonicalScheme = $canonical['scheme'] ?? 'http';

        if (! $canonicalHost) {
            return $next($request);
        }

        if ($request->getHost() === 'www.'.$canonicalHost) {
            return redirect()->to(
                $canonicalScheme.'://'.$canonicalHost.$request->getRequestUri(),
                301,
            );
        }

        return $next($request);
    }
}
