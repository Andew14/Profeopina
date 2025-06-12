<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class ProfileController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $student = Student::findOrFail($id);
        $student->name = $request->name;
        $student->email = $request->email;

        if ($request->password) {
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect()->route('tuperfil')->with('success', 'Perfil actualizado correctamente.');
    }
}
