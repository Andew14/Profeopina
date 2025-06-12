<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;


class ProfesorLogController extends Controller
{
    public function buscarl(Request $request)
    {
        $query = $request->input('profesor');
        $resultados = Profesor::where('nombre', 'LIKE', "%$query%")
            ->orWhere('apellido', 'LIKE', "%$query%")
            ->get();

        return view('busquedalog', compact('query', 'resultados'));
    }
    public function show($id)
    {
    $profesor = Profesor::with('resenias')->findOrFail($id);
    return view('perfil_profelogueado', compact('profesor'));
    }
    public function mostrarPerfill($id)
    {
        $profesor = Profesor::with('resenias')->findOrFail($id);
        $resenias = $profesor->resenias;

        return view('perfilprofelogueado', compact('profesor', 'resenias'));
    }
    public function verReseniasl($id)
    {
        $profesor = Profesor::findOrFail($id);
        $resenias = $profesor->resenias;

        return view('listaresenia', compact('resenias', 'profesor'));
    }
    public function addReview(Request $request, $profesorId)
{
    $request->validate([
        'calificacion' => 'required|integer|min:1|max:5',
        'contenido' => 'required|string',
    ]);

    $profesor = Profesor::findOrFail($profesorId);
    $profesor->resenias()->create([
        'calificacion' => $request->input('calificacion'),
        'contenido' => $request->input('contenido'),
        'user_id' => Auth::id(), // Assuming you have a user_id field to track who created the review
    ]);

    return redirect()->route('perfillog.profesor', ['id' => $profesorId])->with('success', __('messages.review_added'));
}

}
