<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function logout(Request $request)
    {
        // Logout for student guard if present
        if (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        }

        // Logout default web guard (users/admins)
        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate(); // Invalida la sesión

        $request->session()->regenerateToken(); // Regenera el token CSRF

        return redirect('/'); // Redirige a la página principal o a cualquier ruta que prefieras
    }
}
