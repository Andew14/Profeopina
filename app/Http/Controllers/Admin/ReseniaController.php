<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resenia;

class ReseniaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $baseQuery = Resenia::whereHas('profesor', function ($q) use ($user) {
            $q->where('institution_id', $user->institution_id);
        });

        $reportedCount = (clone $baseQuery)->where('denunciada', true)->count();
        $totalCount = (clone $baseQuery)->count();
        $hiddenCount = (clone $baseQuery)->where('oculto', true)->count();
        $visibleCount = (clone $baseQuery)->where('oculto', false)->count();

        // Si no se pasa el parámetro status, por defecto mostramos denunciadas si hay alguna, o todas si no hay
        if (!$request->has('status')) {
            $currentStatus = $reportedCount > 0 ? 'denunciadas' : 'todas';
        } else {
            $currentStatus = $request->input('status');
        }

        $query = (clone $baseQuery)->with(['profesor', 'period']);

        if ($request->filled('search')) {
            $search = strtolower(trim($request->input('search')));
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(contenido) LIKE ?', ["%{$search}%"])
                  ->orWhereHas('profesor', function($pq) use ($search) {
                      $pq->whereRaw('LOWER(nombre) LIKE ?', ["%{$search}%"])
                         ->orWhereRaw('LOWER(apellido) LIKE ?', ["%{$search}%"]);
                  });
            });
        }

        if ($currentStatus === 'denunciadas') {
            $query->where('denunciada', true);
        } elseif ($currentStatus === 'ocultas') {
            $query->where('oculto', true);
        } elseif ($currentStatus === 'visibles') {
            $query->where('oculto', false);
        }

        $order = $request->input('order', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy('id', $order);

        $resenias = $query->get();

        return view('admin.resenias.index', compact('resenias', 'reportedCount', 'totalCount', 'hiddenCount', 'visibleCount', 'currentStatus'));
    }

    public function toggleHidden(Resenia $resenia)
    {
        $this->authorize('toggleHidden', $resenia);

        $resenia->oculto = ! $resenia->oculto;
        $resenia->save();

        return response()->json(['oculto' => $resenia->oculto]);
    }

    public function dismissReport(Resenia $resenia)
    {
        $this->authorize('dismissReport', $resenia);

        $resenia->denunciada = false;
        $resenia->save();

        return response()->json(['denunciada' => $resenia->denunciada]);
    }

    public function destroy(Resenia $resenia)
    {
        $this->authorize('delete', $resenia);

        $resenia->students()->detach();
        $resenia->answers()->delete();
        $resenia->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Reseña eliminada permanentemente del sistema.']);
        }

        return redirect()->back()->with('success', 'La reseña ha sido eliminada completamente del sistema.');
    }
}