<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Clickjacking protection
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // MIME-type sniffing prevention
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');

        $nlpHost = parse_url(config('services.nlp.url', 'http://127.0.0.1:8001'), PHP_URL_HOST) ?? '127.0.0.1';
        $nlpPort = parse_url(config('services.nlp.url', 'http://127.0.0.1:8001'), PHP_URL_PORT);
        $nlpConnect = $nlpPort ? "http://{$nlpHost}:{$nlpPort}" : "http://{$nlpHost}";

        // Content Security Policy (CSP)
        $csp = "default-src 'self'; "
             . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com https://stackpath.bootstrapcdn.com https://cdnjs.cloudflare.com; "
             . "style-src 'self' 'unsafe-inline' https://stackpath.bootstrapcdn.com https://cdnjs.cloudflare.com; "
             . "img-src 'self' data: https: blob:; "
             . "font-src 'self' https://cdnjs.cloudflare.com https://stackpath.bootstrapcdn.com; "
             . "connect-src 'self' http://127.0.0.1:8001 http://localhost:8001 {$nlpConnect}; "
             . "frame-ancestors 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
