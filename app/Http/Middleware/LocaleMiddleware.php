<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    { // Verifica si hay un idioma almacenado en la sesión
        if (session()->has('locale')) {
             // Establece el idioma de la aplicación al idioma almacenado en la sesión
            app()->setLocale(session('locale'));
        }

        return $next($request);
    }
    

}
