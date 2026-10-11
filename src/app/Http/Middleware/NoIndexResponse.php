<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tells search engines never to index this response, regardless of how a
 * crawler reached it (a shared link, a stray backlink, robots.txt only
 * stops crawling, not indexing). Applied to the whole admin panel, login
 * page included.
 */
class NoIndexResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }
}
