<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;
use App\Models\Period;
use App\Models\Question;
use App\Models\Resenia;
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
            try {
                Log::error('Profesor search failed', [
                    'query' => $query,
                    'exception' => $e->getMessage(),
                ]);
            } catch (\Throwable $logEx) {}

            $resultados = collect();
            $error = __('messages.search_error');

            try {
                return response()->view('busqueda', compact('query', 'resultados', 'error'), 200);
            } catch (\Throwable $viewEx) {
                return response($error, 200);
            }
        }
    }

    public function show($id)
    {
        return $this->mostrarPerfil($id);
    }

    public function mostrarPerfil($id)
    {
        // Administradores usan el guard 'web' o están logueados
        $isAdmin = Auth::guard('web')->check();

        // Cargar profesor y sus reseñas (agrupadas por periodo si es necesario)
        $profesor = Profesor::with(['resenias' => function ($query) use ($isAdmin) {
            if (!$isAdmin) {
                $query->where('oculto', false); // Students/Guests solo ven reseñas no ocultas
            }
            $query->with(['period', 'answers.question']);
        }])->findOrFail($id);

        $reseniasPorPeriodo = $profesor->resenias->groupBy(function($resenia) {
            return $resenia->period ? $resenia->period->name : 'General/Antiguo';
        });

        // Solo permitir agregar reseña si hay un periodo activo
        $activePeriod = Period::where('is_active', true)
                              ->where(function($query) {
                                  $query->whereNull('start_time')
                                        ->orWhere('start_time', '<=', now());
                              })
                              ->where(function($query) {
                                  $query->whereNull('end_time')
                                        ->orWhere('end_time', '>=', now());
                              })
                              ->first();

        // Preguntas extra del periodo activo
        $preguntasExtra = collect();
        if ($activePeriod) {
            $preguntasExtra = Question::where('is_active', true)->get();
        }

        return view('perfil_profe', compact('profesor', 'reseniasPorPeriodo', 'isAdmin', 'activePeriod', 'preguntasExtra'));
    }

    public function verResenias($id)
    {
        return $this->mostrarPerfil($id);
    }

    public function addReview(Request $request, $profesorId)
    {
        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'contenido' => 'required|string',
            'answers' => 'nullable|array'
        ]);

        $activePeriod = Period::where('is_active', true)->first();
        if (!$activePeriod) {
            return redirect()->back()->withErrors('No hay un periodo de evaluación activo.');
        }

        $profesor = Profesor::findOrFail($profesorId);
        
        $resenia = $profesor->resenias()->create([
            'calificacion' => $request->input('calificacion'),
            'contenido' => $request->input('contenido'),
            'user_id' => Auth::guard('student')->id(),
            'period_id' => $activePeriod->id,
            'oculto' => false,
        ]);

        if ($request->has('answers') && is_array($request->answers)) {
            foreach ($request->answers as $questionId => $value) {
                $question = Question::find($questionId);
                if ($question) {
                    $resenia->answers()->create([
                        'question_id' => $question->id,
                        'numeric_value' => $question->type === 'likert' ? (int) $value : null,
                        'text_value' => $question->type === 'text' ? (string) $value : null,
                    ]);
                }
            }
        }

        return redirect()->route('perfil.profesor', ['id' => $profesorId])->with('success', __('messages.review_added'));
    }

    // Método para administradores ocultar comentarios
    public function toggleOcultarResenia($profesorId, $reseniaId)
    {
        if (!Auth::guard('web')->check()) {
            abort(403, 'Unauthorized');
        }

        $resenia = Resenia::where('profesor_id', $profesorId)->findOrFail($reseniaId);
        $resenia->oculto = !$resenia->oculto;
        $resenia->save();

        return redirect()->back()->with('success', 'Visibilidad de la reseña actualizada.');
    }
}
