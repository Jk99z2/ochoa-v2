<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Forces every request onto one host+scheme (whatever APP_URL declares),
 * so http:// and www. never exist as live, separately-200ing duplicates of
 * the site - split backlink/ranking signal, flagged as an error by SEO
 * crawlers. Cloudflare/Nginx Proxy Manager sit in front of this app and
 * aren't version-controlled here, so this is enforced at the application
 * layer instead, where it's guaranteed to run regardless of their config.
 *
 * Self-gating: in local dev (and tests) APP_URL is http://localhost:8082,
 * so there's no www to strip and the scheme already matches - nothing to
 * redirect, no separate environment check needed.
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

        $hostMismatch = $request->getHost() === 'www.'.$canonicalHost;
        $schemeMismatch = $canonicalScheme === 'https' && ! $request->isSecure();

        if ($hostMismatch || $schemeMismatch) {
            return redirect()->to(
                $canonicalScheme.'://'.$canonicalHost.$request->getRequestUri(),
                301,
            );
        }

        return $next($request);
    }
}
