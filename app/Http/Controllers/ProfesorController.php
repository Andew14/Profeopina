<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;

class ProfesorController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->input('profesor');
        $resultados = Profesor::where('nombre', 'LIKE', "%$query%")
            ->orWhere('apellido', 'LIKE', "%$query%")
            ->get();

        return view('busqueda', compact('query', 'resultados'));
    }
    public function show($id)
    {
    $profesor = Profesor::with('resenias')->findOrFail($id);
    return view('perfil_profe', compact('profesor'));
    }
    public function mostrarPerfil($id)
    {
        $profesor = Profesor::with('resenias')->findOrFail($id);
        $resenias = $profesor->resenias;

        return view('perfil_profe', compact('profesor', 'resenias'));
    }
    public function verResenias($id)
    {
        $profesor = Profesor::findOrFail($id);
        $resenias = $profesor->resenias;

        return view('listaresenia', compact('resenias', 'profesor'));
    }
}
