<?php

namespace App\Http\Controllers;

use App\Models\Resenia;
use Illuminate\Http\Request;

class ReseniaController extends Controller
{
    public function index()
    {
        $resenias = Resenia::all();
        return view('listaresenia', compact('resenias'));
    }
}
