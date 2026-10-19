<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{
    /**
     * Campos que no deben ser sanitizados con strip_tags (como contraseñas).
     */
    protected array $except = [
        'password',
        'password_confirmation',
        'current_password',
        '_token',
    ];

    /**
     * Handle an incoming request and sanitize inputs.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if (is_string($value) && !in_array($key, $this->except, true)) {
                // Remover null bytes
                $value = str_replace(chr(0), '', $value);
                // Sanitizar HTML / JavaScript tags peligrosos
                $value = strip_tags($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
