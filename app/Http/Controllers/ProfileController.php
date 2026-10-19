<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class ProfileController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $id,
            'password' => 'nullable|string|min:8',
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

    public function destroy(Request $request)
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('login.student');
        }

        $request->validate([
            'password_delete' => 'required|string',
        ], [
            'password_delete.required' => 'Por seguridad, debes ingresar tu contraseña para eliminar tu cuenta.',
        ]);

        if (!Hash::check($request->password_delete, $student->password)) {
            return redirect()->back()->withErrors([
                'password_delete' => 'La contraseña ingresada no es correcta.',
            ]);
        }

        DB::transaction(function () use ($student) {
            // Desvincular reseñas asociadas
            $resenias = $student->resenias()->get();
            foreach ($resenias as $resenia) {
                if (!$resenia->procesada) {
                    $resenia->students()->detach();
                    $resenia->answers()->delete();
                    $resenia->delete();
                } else {
                    $resenia->students()->detach();
                }
            }

            // Eliminar registro del estudiante
            $student->delete();
        });

        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada permanentemente.');
    }
}
