<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $institutionId = auth()->user()->institution_id;
        $courses = Course::where('institution_id', $institutionId)->get();

        return view('admin.courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $institutionId = auth()->user()->institution_id;

        $exists = Course::where('name', $request->input('name'))
            ->where('institution_id', $institutionId)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'El curso ya está registrado en esta institución.');
        }

        Course::create([
            'name' => $request->input('name'),
            'institution_id' => $institutionId,
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Curso creado con éxito.');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);

        if ($course->institution_id !== auth()->user()->institution_id) {
            abort(403, 'No tienes autorización para editar este curso.');
        }

        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        if ($course->institution_id !== auth()->user()->institution_id) {
            abort(403, 'No tienes autorización para editar este curso.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $exists = Course::where('name', $request->input('name'))
            ->where('institution_id', $course->institution_id)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Ya existe otro curso con el mismo nombre en esta institución.');
        }

        $course->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Curso actualizado con éxito.');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->institution_id !== auth()->user()->institution_id) {
            abort(403, 'No tienes autorización para eliminar este curso.');
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado con éxito.');
    }
}
