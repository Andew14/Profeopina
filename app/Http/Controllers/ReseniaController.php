<?php

namespace App\Http\Controllers;

use App\Models\Resenia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReseniaController extends Controller
{
    public function index()
    {
        $resenias = Resenia::all();
        return view('listaresenia', compact('resenias'));
    }

    public function denunciar(Resenia $resenia)
    {
        if (!Auth::guard('student')->check() && !Auth::guard('web')->check()) {
            return redirect()->back()->with('error', 'Solo los usuarios registrados pueden denunciar una reseña.');
        }

        if (Auth::guard('student')->check() && $resenia->students->contains(Auth::guard('student')->id())) {
            return redirect()->back()->with('error', 'No puedes denunciar tu propio comentario.');
        }

        $resenia->denunciada = true;
        $resenia->save();

        return redirect()->back()->with('success', 'La reseña ha sido denunciada y será revisada por el administrador.');
    }
}
