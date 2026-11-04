<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limiter para autenticación (previene fuerza bruta sin bloquear usuarios simultáneos)
        RateLimiter::for('auth', function (Request $request) {
            $email = strtolower(trim((string) $request->input('email', '')));
            $key = $email !== '' ? ($email . '|' . $request->ip()) : $request->ip();

            return Limit::perMinute(30)->by($key)->response(function () {
                return response('Demasiados intentos de acceso. Por favor intenta nuevamente en 1 minuto.', 429);
            });
        });

        // Rate limiter para registros de estudiantes
        // Configurado a 60 por minuto para no bloquear aulas completas o redes universitarias (NAT/Wi-Fi)
        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip())->response(function () {
                return response('Límite de solicitudes de registro temporalmente alcanzado. Por favor intenta en 1 minuto.', 429);
            });
        });

        // Rate limiter para publicación y edición de reseñas (previene spam y saturación de IA)
        RateLimiter::for('reviews', function (Request $request) {
            $userKey = optional($request->user('student'))->id ?: $request->ip();
            return Limit::perMinute(20)->by((string) $userKey)->response(function () {
                return response()->json(['error' => 'Has alcanzado el límite de reseñas por minuto. Espera un momento.'], 429);
            });
        });

        // Rate limiter para denuncias de reseñas
        RateLimiter::for('reports', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip())->response(function () {
                return response()->json(['error' => 'Demasiadas solicitudes de denuncia. Intenta más tarde.'], 429);
            });
        });
    }
}
