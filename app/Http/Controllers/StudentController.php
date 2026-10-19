<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Course;
use App\Models\Period;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('student')->check()) {
            return redirect()->route('iniciologueado');
        }
        return view('auth.login_student');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials)) {
            Log::info('Student login successful', [
                'student_id' => Auth::guard('student')->id(),
                'intended' => session('url.intended'),
            ]);

            $request->session()->regenerate();

            $intended = session()->pull('url.intended', null);
            if ($intended && $intended !== route('login') && $intended !== route('login.student')) {
                return redirect()->to($intended);
            }

            return redirect()->route('iniciologueado');
        }

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        Log::warning('Login failed for student and web guards', ['email' => $request->input('email')]);

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
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('student')->login($student);
        $request->session()->regenerate();

        return redirect()->route('iniciologueado');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function editProfile()
    {
        $student = Auth::guard('student')->user();
        return view('edit_profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $student->id,
            'password' => 'nullable|string|min:8',
        ]);

        $student->name = $request->name;
        $student->email = $request->email;

        if ($request->filled('password')) {
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect()->route('tuperfil')->with('success', 'Perfil actualizado correctamente.');
    }

    public function showProfile()
    {
        Log::info('Accessing student profile', [
            'is_authenticated' => Auth::guard('student')->check(),
            'student_id' => Auth::guard('student')->id(),
            'session_id' => request()->session()->getId(),
        ]);

        $student = Auth::guard('student')->user();
        return view('tuperfil', compact('student'));
    }

    public function showReviews()
    {
        $student = Auth::guard('student')->user();
        $resenias = $student->resenias()->with('profesor', 'period')->latest()->get();
        $courses = Course::orderBy('name', 'asc')->get();
        $periods = Period::orderBy('id', 'desc')->get();
        return view('turesenia', compact('resenias', 'student', 'courses', 'periods'));
    }
}
