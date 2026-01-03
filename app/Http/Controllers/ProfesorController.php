<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfesorController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->input('profesor');

        try {
            $resultados = Profesor::where('nombre', 'LIKE', "%$query%")
                ->orWhere('apellido', 'LIKE', "%$query%")
                ->get();

            return view('busqueda', compact('query', 'resultados'));
        } catch (\Throwable $e) {
            // Try to log the exception, but be defensive: logging should never cause a new failure
            try {
                Log::error('Profesor search failed', [
                    'query' => $query,
                    'exception' => $e->getMessage(),
                ]);
            } catch (\Throwable $logEx) {
                // swallow logging errors to avoid cascade failures
            }

            // Return a friendly message and empty results to the view. Use response()->view
            // and a small fallback in case the view rendering itself fails.
            $resultados = collect();
            $error = __('messages.search_error');

            try {
                return response()->view('busqueda', compact('query', 'resultados', 'error'), 200);
            } catch (\Throwable $viewEx) {
                // Fallback to a plain text response so users don't get a 500
                return response($error, 200);
            }
        }
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
            'user_id' => Auth::guard('student')->id(), // Use student guard
        ]);

        return redirect()->route('perfil.profesor', ['id' => $profesorId])->with('success', __('messages.review_added'));
    }
}
