<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resenia;

class ReseniaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = request()->user();

        $resenias = Resenia::whereHas('profesor', function ($q) use ($user) {
            $q->where('institution_id', $user->institution_id);
        })->with('profesor')->get();

        return view('admin.resenias.index', compact('resenias'));
    }

    public function toggleHidden(Resenia $resenia)
    {
        $this->authorize('toggleHidden', $resenia);

        $resenia->oculto = ! $resenia->oculto;
        $resenia->save();

        return response()->json(['oculto' => $resenia->oculto]);
    }
}