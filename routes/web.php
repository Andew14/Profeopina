<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\ReseniaController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;

// Rutas generales
Route::get('/', function () {
    if (auth('web')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('inicio');
})->name('inicio');

Route::get('/iniciarsesion', function () {
    return view('iniciarsesion');
})->name('iniciarsesion');

Route::get('/registro', function () {
    return view('registro');
})->name('registro');

Route::get('/contactanos', function () {
    return view('contactanos');
})->name('contactanos');

Route::get('/perfil', function () {
    return view('perfil_profe');
})->name('perfil');

// Ruta para mostrar el formulario de inicio de sesión
Route::get('/login_student', [StudentController::class, 'showLoginForm'])->name('login.student');
Route::post('/login_student', [StudentController::class, 'login'])->middleware('throttle:auth')->name('login.student.post');

// Admin login routes (separate form for admins/users)
Route::get('/login_admin', [\App\Http\Controllers\AdminAuthController::class, 'showLoginForm'])->name('login.admin');
Route::post('/login_admin', [\App\Http\Controllers\AdminAuthController::class, 'login'])->middleware('throttle:auth')->name('login.admin.post');
Route::post('/logout_admin', [\App\Http\Controllers\AdminAuthController::class, 'logout'])->name('logout.admin');

// Compatibility route for Laravel's auth middleware that expects route('login')
// Render the student login form directly so tests expecting 200 succeed
Route::get('/login', [StudentController::class, 'showLoginForm'])->name('login');

// Safe redirect for any legacy /dashboard requests
Route::get('/dashboard', function () {
    if (auth('web')->check()) {
        return redirect()->route('admin.dashboard');
    }
    if (auth('student')->check()) {
        return redirect()->route('iniciologueado');
    }
    return redirect()->route('inicio');
})->name('dashboard');

Route::get('/register_student', [StudentController::class, 'create'])->name('register.student');
Route::post('/register_student', [StudentController::class, 'store'])->middleware('throttle:register');

// Rutas específicas con controladores
Route::get('/profesor/{id}/reseñas', [ProfesorController::class, 'verResenias'])->name('profesor.resenias');
Route::get('/buscar_profesor', [ProfesorController::class, 'buscar'])->name('buscar.profesor');
Route::get('/perfil_profesor/{id}', [ProfesorController::class, 'mostrarPerfil'])->name('perfil.profesor');
Route::get('/listaresenia', [ReseniaController::class, 'index'])->name('listaresenia');
Route::post('/resenia/{resenia}/denunciar', [ReseniaController::class, 'denunciar'])->middleware('throttle:reports')->name('resenias.denunciar');



// Ruta para el cambio de idioma
Route::get('locale/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
})->name('locale.change');

// Only allow language routes for specific locales to avoid capturing other paths
Route::get('/{lang}', function ($lang) {
    $allowed = ['en', 'es'];
    if (! in_array($lang, $allowed)) {
        return redirect()->route('inicio');
    }

    App::setLocale($lang);
    session(['locale' => $lang]);
    return redirect()->back();
})->where('lang', 'en|es');

Route::middleware('auth:student')->group(function () {
    Route::get('/iniciologueado', function () {
        return view('iniciologueado');
    })->name('iniciologueado');

    Route::get('/tuperfil', [StudentController::class, 'showProfile'])->name('tuperfil');
    
    Route::get('/turesenia', [StudentController::class, 'showReviews'])->name('turesenia');
    
    Route::get('/profeguardado', function () {
        return view('profguardados');
    })->name('profeguardado');
    
    Route::get('/configuracion', function () {
        return view('configcuenta');
    })->name('configuracion');

    Route::post('/profesor/{profesor}/add_review', [ProfesorController::class, 'addReview'])->middleware('throttle:reviews')->name('add_review');
    Route::post('/profesor/{profesorId}/resenia/{reseniaId}/toggle', [ProfesorController::class, 'toggleOcultarResenia'])->name('profesor.resenia.toggle');
    Route::put('/resenia/editar/{id}', [ProfesorController::class, 'editReview'])->middleware('throttle:reviews')->name('resenia.editar');
    Route::delete('/resenia/eliminar/{id}', [ProfesorController::class, 'deleteReview'])->name('resenia.eliminar');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes (each admin scoped to their institution via policies)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('profesors', \App\Http\Controllers\Admin\ProfesorController::class)->except(['show']);
    Route::post('profesors/{profesor}/toggle-active', [\App\Http\Controllers\Admin\ProfesorController::class, 'toggleActive'])->name('profesors.toggleActive');

    Route::get('resenias', [\App\Http\Controllers\Admin\ReseniaController::class, 'index'])->name('resenias.index');
    Route::post('resenias/{resenia}/toggle-hidden', [\App\Http\Controllers\Admin\ReseniaController::class, 'toggleHidden'])->name('resenias.toggleHidden');
    Route::post('resenias/{resenia}/dismiss-report', [\App\Http\Controllers\Admin\ReseniaController::class, 'dismissReport'])->name('resenias.dismissReport');
    Route::delete('resenias/{resenia}', [\App\Http\Controllers\Admin\ReseniaController::class, 'destroy'])->name('resenias.destroy');

    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class)->except(['show', 'create']);
    Route::resource('periods', \App\Http\Controllers\Admin\PeriodController::class)->except(['show', 'create']);
});
