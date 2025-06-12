<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario

        $request->session()->invalidate(); // Invalida la sesión

        $request->session()->regenerateToken(); // Regenera el token CSRF

        return redirect('/'); // Redirige a la página principal o a cualquier ruta que prefieras
    }
}
