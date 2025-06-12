<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las ventas de la base de datos
        $ventas = Venta::all();
        // Retornar la vista de índice con los datos de las ventas
        return view('index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retornar la vista de creación
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos enviados por el formulario
        $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'cantidad' => 'required|integer',
            'precio' => 'required|numeric',
            'cliente' => 'required|string|max:255',
            'fecha_venta' => 'required|date',
        ]);

        // Crear una nueva venta con los datos validados
        Venta::create([
            'nombre_producto' => $request->nombre_producto,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'cliente' => $request->cliente,
            'fecha_venta' => $request->fecha_venta,
        ]);

        // Redirigir al índice de ventas con un mensaje de éxito
        return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        // Retornar la vista de detalle con la información de la venta
        return view('show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        // Retornar la vista de edición con los datos de la venta
        return view('edit', compact('venta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        // Validar los datos enviados por el formulario
        $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'cantidad' => 'required|integer',
            'precio' => 'required|numeric',
            'cliente' => 'required|string|max:255',
            'fecha_venta' => 'required|date',
        ]);

        // Actualizar la venta con los datos validados
        $venta->update([
            'nombre_producto' => $request->nombre_producto,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'cliente' => $request->cliente,
            'fecha_venta' => $request->fecha_venta,
        ]);

        // Redirigir al índice de ventas con un mensaje de éxito
        return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    $venta = Venta::findOrFail($id);
    $venta->delete();

    return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }
}
