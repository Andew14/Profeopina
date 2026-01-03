<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            // Log successful attempt and intended URL for debugging
            Log::info('Student login successful', [
                'student_id' => Auth::guard('student')->id(),
                'intended' => session('url.intended'),
            ]);

            $request->session()->regenerate();

            // If the intended URL is the login page itself, ignore it and redirect to iniciologueado
            $intended = session()->pull('url.intended', null);
            if ($intended && $intended !== route('login') && $intended !== route('login.student')) {
                return redirect()->to($intended);
            }

            return redirect()->route('iniciologueado');
        }

        Log::warning('Student login failed', ['email' => $request->input('email')]);

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
        $request->session()->regenerate();

        return redirect()->route('iniciologueado');
    }
    public function showProfile()
    {
        // Debug info: log whether the student is authenticated when accessing profile
        Log::info('Accessing student profile', [
            'is_authenticated' => Auth::guard('student')->check(),
            'student_id' => Auth::guard('student')->id(),
            'session_id' => request()->session()->getId(),
        ]);

        $student = Auth::guard('student')->user();
        return view('tuperfil', compact('student'));
    }


}
