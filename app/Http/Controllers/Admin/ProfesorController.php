<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profesor;

class ProfesorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $profesors = Profesor::where('institution_id', $user->institution_id)->get();

        return view('admin.profesors.index', compact('profesors'));
    }

    public function create()
    {
        $this->authorize('create', Profesor::class);
        return view('admin.profesors.create');
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
        ]);

        // Si no se proporciona activo, por defecto es true
        if (!isset($data['activo'])) {
            $data['activo'] = true;
        }

        $data['institution_id'] = $request->user()->institution_id;

        $profesor = Profesor::create($data);

        return redirect()->route('admin.profesors.index')->with('success', 'Profesor creado');
    }

    public function edit(Profesor $profesor)
    {
        $this->authorize('update', $profesor);
        return view('admin.profesors.edit', compact('profesor'));
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
        ]);

        // Si no se proporciona activo, mantener el valor actual
        if (!isset($data['activo'])) {
            unset($data['activo']);
        }

        $profesor->update($data);

        return redirect()->route('admin.profesors.index')->with('success', 'Profesor actualizado');
    }

    public function toggleActive(Profesor $profesor)
    {
        $this->authorize('toggleActive', $profesor);

        $profesor->activo = ! $profesor->activo;
        $profesor->save();

        return response()->json(['activo' => $profesor->activo]);
    }
}