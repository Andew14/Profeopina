<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;
use App\Models\Period;
use App\Models\Question;
use App\Models\Resenia;
use App\Models\Course;
use App\Jobs\ProcessReviewNLP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProfesorController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->input('profesor');

        try {
            $driver = DB::connection()->getDriverName();
            $resultados = Profesor::with(['resenias' => function($q) {
                $q->whereNotNull('contenido')
                  ->where('contenido', '!=', '[Comentario eliminado por el estudiante]');
            }, 'institution', 'courses'])
            ->where(function($q) use ($query, $driver) {
                $q->where('nombre', 'LIKE', "%$query%")
                  ->orWhere('apellido', 'LIKE', "%$query%");
                
                if ($driver === 'sqlite') {
                    $q->orWhere(DB::raw("nombre || ' ' || apellido"), 'LIKE', "%$query%");
                } else {
                    $q->orWhere(DB::raw("CONCAT(nombre, ' ', apellido)"), 'LIKE', "%$query%");
                }
            })
            ->orderBy('nombre', 'asc')
            ->orderBy('apellido', 'asc')
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

        // Cargar profesor y sus reseñas activas (excluyendo comentarios eliminados)
        $profesor = Profesor::with(['resenias' => function ($query) use ($isAdmin) {
            $query->whereNotNull('contenido')
                  ->where('contenido', '!=', '[Comentario eliminado por el estudiante]');
            if (!$isAdmin) {
                $query->where('oculto', false); // Students/Guests solo ven reseñas no ocultas
            }
            $query->with(['period', 'answers.question', 'students']);
        }])->findOrFail($id);

        $reseniasPorPeriodo = $profesor->resenias->groupBy(function($resenia) {
            return $resenia->period ? $resenia->period->name : 'General/Antiguo';
        });

        // Obtener periodos ordenados del más reciente al más antiguo
        $activePeriod = Period::where('is_active', true)->first();
        $allPeriods = Period::orderBy('id', 'desc')->get();

        // Calcular valoración promedio de las reseñas activas
        $allReseniasProfesor = Resenia::where('profesor_id', $id)
            ->whereNotNull('contenido')
            ->where('contenido', '!=', '[Comentario eliminado por el estudiante]')
            ->get();
        $avgRating = $allReseniasProfesor->avg('calificacion');
        $totalReviewsCount = $allReseniasProfesor->count();

        // Desglose de estrellas estilo Google Play (5, 4, 3, 2, 1)
        $starBreakdown = [
            5 => ['count' => 0, 'percentage' => 0],
            4 => ['count' => 0, 'percentage' => 0],
            3 => ['count' => 0, 'percentage' => 0],
            2 => ['count' => 0, 'percentage' => 0],
            1 => ['count' => 0, 'percentage' => 0],
        ];

        if ($totalReviewsCount > 0) {
            foreach ($allReseniasProfesor as $res) {
                $rating = (int) $res->calificacion;
                if (isset($starBreakdown[$rating])) {
                    $starBreakdown[$rating]['count']++;
                }
            }
            foreach ($starBreakdown as $rating => &$data) {
                $data['percentage'] = round(($data['count'] / $totalReviewsCount) * 100);
            }
            unset($data);
        }

        // --- CALCULAR DATOS PARA GRÁFICOS (CHART.JS) ---

        // 1. Histograma de evolución histórica (Calificación promedio por semestre)
        $histogramData = [];
        foreach ($reseniasPorPeriodo as $periodName => $group) {
            $avg = $group->avg('calificacion');
            $histogramData[$periodName] = $avg ? round($avg, 2) : 0;
        }

        // 2. Comparativo de Mitad vs Fin de ciclo (para el periodo activo)
        $chartComparison = [
            'has_data' => false,
            'mid_scores' => [0, 0, 0, 0],
            'end_scores' => [0, 0, 0, 0],
            'period_name' => $activePeriod ? $activePeriod->name : '',
        ];

        if ($activePeriod && $activePeriod->mid_time) {
            $reviewsActivePeriod = $profesor->resenias->where('period_id', $activePeriod->id);
            $midReviews = $reviewsActivePeriod->where('created_at', '<=', $activePeriod->mid_time);
            $endReviews = $reviewsActivePeriod->where('created_at', '>', $activePeriod->mid_time);

            $dimensionsList = [1, 2, 3, 4];
            $hasMidAnswers = false;
            $hasEndAnswers = false;

            foreach ($dimensionsList as $index => $qId) {
                // Mitad
                $midAvg = $midReviews->flatMap->answers->where('question_id', $qId)->avg('numeric_value');
                $chartComparison['mid_scores'][$index] = $midAvg ? round($midAvg, 2) : 0;
                if ($midAvg) {
                    $hasMidAnswers = true;
                }

                // Fin
                $endAvg = $endReviews->flatMap->answers->where('question_id', $qId)->avg('numeric_value');
                $chartComparison['end_scores'][$index] = $endAvg ? round($endAvg, 2) : 0;
                if ($endAvg) {
                    $hasEndAnswers = true;
                }
            }

            if ($hasMidAnswers || $hasEndAnswers) {
                $chartComparison['has_data'] = true;
            }
        }

        // Métricas por periodo (All + cada periodo individual)
        $metricsByPeriod = [];
        $allReseniasLoaded = $profesor->resenias;

        // All (Histórico Completo)
        $allDimensions = [];
        foreach ([1, 2, 3, 4] as $qId) {
            $ans = $allReseniasLoaded->flatMap->answers->where('question_id', $qId);
            $allDimensions[$qId] = $ans->count() > 0 ? round($ans->avg('numeric_value'), 1) : null;
        }

        $metricsByPeriod['all'] = [
            'avg_rating' => $avgRating ? number_format($avgRating, 1) : 'N/A',
            'stars_count' => round($avgRating ?? 0),
            'total_reviews' => $totalReviewsCount,
            'star_breakdown' => $starBreakdown,
            'dimensions' => $allDimensions
        ];

        foreach ($allPeriods as $p) {
            $pReviews = $allReseniasLoaded->where('period_id', $p->id);
            $pCount = $pReviews->count();
            $pAvg = $pCount > 0 ? $pReviews->avg('calificacion') : 0;

            $pStarMap = [
                5 => ['count' => 0, 'percentage' => 0],
                4 => ['count' => 0, 'percentage' => 0],
                3 => ['count' => 0, 'percentage' => 0],
                2 => ['count' => 0, 'percentage' => 0],
                1 => ['count' => 0, 'percentage' => 0],
            ];

            if ($pCount > 0) {
                foreach ($pReviews as $res) {
                    $rVal = (int) $res->calificacion;
                    if (isset($pStarMap[$rVal])) {
                        $pStarMap[$rVal]['count']++;
                    }
                }
                foreach ($pStarMap as $rVal => &$pData) {
                    $pData['percentage'] = round(($pData['count'] / $pCount) * 100);
                }
                unset($pData);
            }

            $pDimensions = [];
            foreach ([1, 2, 3, 4] as $qId) {
                $pAns = $pReviews->flatMap->answers->where('question_id', $qId);
                $pDimensions[$qId] = $pAns->count() > 0 ? round($pAns->avg('numeric_value'), 1) : null;
            }

            $metricsByPeriod[$p->id] = [
                'avg_rating' => $pCount > 0 ? number_format($pAvg, 1) : 'N/A',
                'stars_count' => round($pAvg ?? 0),
                'total_reviews' => $pCount,
                'star_breakdown' => $pStarMap,
                'dimensions' => $pDimensions
            ];
        }

        $preguntasExtra = collect();
        $courses = $profesor->courses;

        return view('perfil_profe', compact(
            'profesor',
            'reseniasPorPeriodo',
            'isAdmin',
            'activePeriod',
            'allPeriods',
            'preguntasExtra',
            'avgRating',
            'totalReviewsCount',
            'starBreakdown',
            'histogramData',
            'chartComparison',
            'courses',
            'metricsByPeriod'
        ));
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
            'curso' => 'required|string|exists:courses,name',
            'period_id' => 'required|exists:periods,id',
        ]);

        $profesor = Profesor::findOrFail($profesorId);
        
        $resenia = $profesor->resenias()->create([
            'calificacion' => $request->input('calificacion'),
            'contenido' => $request->input('contenido'),
            'curso' => $request->input('curso'),
            'period_id' => $request->input('period_id'),
            'oculto' => false,
            'procesada' => false,
        ]);

        if (Auth::guard('student')->check()) {
            $resenia->students()->attach(Auth::guard('student')->id());
        }

        // Despachar el análisis NLP en segundo plano
        ProcessReviewNLP::dispatch($resenia);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.review_added'),
                'profesor_id' => $profesorId,
            ]);
        }

        return redirect()->route('perfil.profesor', ['id' => $profesorId])->with('success', __('messages.review_added'));
    }

    public function editReview(Request $request, $id)
    {
        $resenia = Resenia::with('students')->findOrFail($id);
        
        $studentId = Auth::guard('student')->id();
        if (!$resenia->students->contains($studentId)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'No tienes permiso para editar este comentario.'], 403);
            }
            abort(403, 'No tienes permiso para editar este comentario.');
        }

        if (is_null($resenia->contenido) || $resenia->contenido === '[Comentario eliminado por el estudiante]') {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'No puedes editar una reseña que ya fue eliminada.'], 403);
            }
            abort(403, 'No puedes editar una reseña que ya fue eliminada.');
        }

        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'contenido' => 'required|string',
            'curso' => 'required|string|exists:courses,name',
            'period_id' => 'required|exists:periods,id',
        ]);

        // Actualizar datos de la reseña
        $resenia->update([
            'calificacion' => $request->input('calificacion'),
            'contenido' => $request->input('contenido'),
            'curso' => $request->input('curso'),
            'period_id' => $request->input('period_id'),
            'procesada' => false,
        ]);

        // Limpiar las respuestas de aspectos anteriores y re-ejecutar inmediatamente el análisis NLP
        $resenia->answers()->delete();
        ProcessReviewNLP::dispatchSync($resenia);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comentario actualizado con éxito. El análisis de IA se ha vuelto a procesar.',
                'profesor_id' => $resenia->profesor_id,
            ]);
        }

        // Redireccionar explícitamente a la ruta de origen sin atrapar el historial del navegador
        $redirectTo = $request->input('redirect_to');
        if ($redirectTo === 'turesenia') {
            return redirect()->route('turesenia')->with('success', 'Comentario actualizado con éxito. El análisis de IA se ha vuelto a procesar.');
        }

        return redirect()->route('perfil.profesor', ['id' => $resenia->profesor_id])->with('success', 'Comentario actualizado con éxito. El análisis de IA se ha vuelto a procesar.');
    }

    public function deleteReview(Request $request, $id)
    {
        $resenia = Resenia::with('students')->findOrFail($id);

        $studentId = Auth::guard('student')->id();
        if (!$resenia->students->contains($studentId)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'No tienes permiso para eliminar este comentario.'], 403);
            }
            abort(403, 'No tienes permiso para eliminar este comentario.');
        }

        if (!$resenia->procesada) {
            $resenia->students()->detach();
            $resenia->answers()->delete();
            $resenia->delete();
        } else {
            $resenia->update([
                'contenido' => '[Comentario eliminado por el estudiante]',
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'El comentario ha sido eliminado con éxito.']);
        }

        return redirect()->back()->with('success', 'El comentario ha sido eliminado con éxito.');
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
