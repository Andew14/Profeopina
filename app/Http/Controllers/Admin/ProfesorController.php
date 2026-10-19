<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profesor;
use App\Models\Institution;

class ProfesorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Profesor::where('institution_id', $user->institution_id);

        if ($request->filled('search')) {
            $search = strtolower(trim($request->input('search')));
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nombre) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(apellido) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('course_id')) {
            $courseId = $request->input('course_id');
            $query->whereHas('courses', function($q) use ($courseId) {
                $q->where('courses.id', $courseId);
            });
        } elseif ($request->filled('course_search')) {
            $courseSearch = strtolower(trim($request->input('course_search')));
            $query->whereHas('courses', function($q) use ($courseSearch) {
                $q->whereRaw('LOWER(courses.name) LIKE ?', ["%{$courseSearch}%"]);
            });
        }

        $order = $request->input('order', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy('nombre', $order)->orderBy('apellido', $order);

        $profesors = $query->get();
        $courses = \App\Models\Course::where('institution_id', $user->institution_id)->orderBy('name', 'asc')->get();

        return view('admin.profesors.index', compact('profesors', 'courses'));
    }

    public function create()
    {
        $this->authorize('create', Profesor::class);
        $user = auth()->user();
        $institutions = Institution::all();
        $courses = \App\Models\Course::where('institution_id', $user->institution_id)->get();
        return view('admin.profesors.create', compact('courses', 'institutions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Profesor::class);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|string|max:255',
            'activo' => 'nullable|boolean',
            'institution_id' => 'nullable|exists:institutions,id',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);

        if (!isset($data['activo'])) {
            $data['activo'] = true;
        }

        if (!isset($data['institution_id']) || !$data['institution_id']) {
            $data['institution_id'] = $request->user()->institution_id;
        }

        $profesor = Profesor::create($data);

        if ($request->has('courses')) {
            $profesor->courses()->sync($request->input('courses', []));
        }

        return redirect()->route('admin.profesors.index')->with('success', "El profesor {$profesor->nombre} {$profesor->apellido} ha sido añadido con éxito.");
    }

    public function edit(Profesor $profesor)
    {
        $this->authorize('update', $profesor);
        $institutions = Institution::all();
        $courses = \App\Models\Course::where('institution_id', $profesor->institution_id)->get();
        $assignedCourseIds = $profesor->courses->pluck('id')->toArray();
        return view('admin.profesors.edit', compact('profesor', 'institutions', 'courses', 'assignedCourseIds'));
    }

    public function update(Request $request, Profesor $profesor)
    {
        $this->authorize('update', $profesor);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto' => 'nullable|string|max:255',
            'activo' => 'nullable|boolean',
            'institution_id' => 'required|exists:institutions,id',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);

        if (!isset($data['activo'])) {
            unset($data['activo']);
        }

        $profesor->update($data);

        $profesor->courses()->sync($request->input('courses', []));

        return redirect()->route('admin.profesors.index')->with('success', 'Profesor actualizado exitosamente.');
    }

    public function toggleActive(Profesor $profesor)
    {
        $this->authorize('toggleActive', $profesor);

        $profesor->activo = ! $profesor->activo;
        $profesor->save();

        return response()->json(['activo' => $profesor->activo]);
    }

    public function destroy(Profesor $profesor)
    {
        $this->authorize('delete', $profesor);

        $profesor->courses()->detach();
        foreach ($profesor->resenias as $resenia) {
            $resenia->students()->detach();
            $resenia->answers()->delete();
            $resenia->delete();
        }
        $profesor->delete();

        return redirect()->route('admin.profesors.index')->with('success', 'Profesor eliminado exitosamente del sistema.');
    }
}