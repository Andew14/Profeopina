<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_student');
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::guard('student')->attempt($credentials)) {
        return redirect()->route('iniciologueado');
    }

    return redirect()->back()->withErrors([
        'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
    ])->withInput($request->only('email'));
}


    public function create()
    {
        return view('auth.register_student');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Asegúrate de que se usa Hash::make
        ]);

        Auth::guard('student')->login($student);

        return redirect()->route('login.student');
    }
    public function showProfile()
    {
        $student = Auth::guard('student')->user();
        return view('tuperfil', compact('student'));
    }


}
