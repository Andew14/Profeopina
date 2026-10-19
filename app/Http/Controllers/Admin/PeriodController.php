<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use Carbon\Carbon;

class PeriodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $periods = Period::orderBy('start_time', 'desc')->get();
        return view('admin.periods.index', compact('periods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'nullable|boolean',
        ]);

        $start = Carbon::parse($request->input('start_time'))->startOfDay();
        $end = Carbon::parse($request->input('end_time'))->endOfDay();

        // 1. Detección de solapamiento (Overlap detection)
        $overlap = Period::where(function($q) use ($start, $end) {
            $q->where('start_time', '<=', $end)
              ->where('end_time', '>=', $start);
        })->first();

        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->with('error', "El rango de fechas seleccionado se solapa con el ciclo existente: '{$overlap->name}' ({$overlap->start_time->format('d/m/Y')} a {$overlap->end_time->format('d/m/Y')}).");
        }

        // 2. Calcular punto medio automáticamente
        $diffSeconds = $start->diffInSeconds($end);
        $mid = $start->copy()->addSeconds($diffSeconds / 2);

        $isActive = $request->boolean('is_active', false);

        if ($isActive) {
            Period::where('is_active', true)->update(['is_active' => false]);
        }

        Period::create([
            'name' => $request->input('name'),
            'start_time' => $start,
            'end_time' => $end,
            'mid_time' => $mid,
            'is_active' => $isActive,
            'mid_analysis_run' => false,
            'end_analysis_run' => false,
        ]);

        return redirect()->route('admin.periods.index')->with('success', 'Ciclo académico creado correctamente.');
    }

    public function edit($id)
    {
        $period = Period::findOrFail($id);
        return view('admin.periods.edit', compact('period'));
    }

    public function update(Request $request, $id)
    {
        $period = Period::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_active' => 'nullable|boolean',
        ]);

        $start = Carbon::parse($request->input('start_time'))->startOfDay();
        $end = Carbon::parse($request->input('end_time'))->endOfDay();

        // 1. Detección de solapamiento (excluyendo a sí mismo)
        $overlap = Period::where('id', '!=', $id)
            ->where(function($q) use ($start, $end) {
                $q->where('start_time', '<=', $end)
                  ->where('end_time', '>=', $start);
            })->first();

        if ($overlap) {
            return redirect()->back()
                ->withInput()
                ->with('error', "El rango de fechas se solapa con el ciclo existente: '{$overlap->name}' ({$overlap->start_time->format('d/m/Y')} a {$overlap->end_time->format('d/m/Y')}).");
        }

        // 2. Calcular punto medio automáticamente
        $diffSeconds = $start->diffInSeconds($end);
        $mid = $start->copy()->addSeconds($diffSeconds / 2);

        $isActive = $request->boolean('is_active', false);

        if ($isActive && !$period->is_active) {
            Period::where('is_active', true)->update(['is_active' => false]);
        }

        $period->update([
            'name' => $request->input('name'),
            'start_time' => $start,
            'end_time' => $end,
            'mid_time' => $mid,
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.periods.index')->with('success', 'Ciclo académico actualizado correctamente.');
    }

    public function destroy($id)
    {
        $period = Period::findOrFail($id);
        
        // Desvincular de forma segura las reseñas asignadas al ciclo para preservar el historial
        $period->resenias()->update(['period_id' => null]);

        $period->delete();
        return redirect()->route('admin.periods.index')->with('success', 'Ciclo académico eliminado correctamente.');
    }
}
