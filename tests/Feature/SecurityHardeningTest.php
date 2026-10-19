<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Profesor;
use App\Models\Course;
use App\Models\Period;
use App\Models\Resenia;
use App\Models\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('auth');
        RateLimiter::clear('register');
        RateLimiter::clear('reviews');
        RateLimiter::clear('reports');
    }

    protected function createStudent(array $attributes = []): Student
    {
        static $counter = 1;
        return Student::create(array_merge([
            'name' => 'Estudiante ' . $counter,
            'email' => 'estudiante' . ($counter++) . '_' . time() . '@example.com',
            'password' => Hash::make('SecretPass123!'),
        ], $attributes));
    }

    protected function createInstitution(string $name = 'UNAS'): Institution
    {
        return Institution::firstOrCreate(['name' => $name]);
    }

    protected function createProfesor(Institution $inst, array $attributes = []): Profesor
    {
        static $prCounter = 1;
        return Profesor::create(array_merge([
            'nombre' => 'Docente ' . $prCounter,
            'apellido' => 'Prueba ' . ($prCounter++),
            'descripcion' => 'Docente universitario',
            'institution_id' => $inst->id,
            'activo' => true,
        ], $attributes));
    }

    protected function createPeriod(array $attributes = []): Period
    {
        static $pCounter = 1;
        return Period::create(array_merge([
            'name' => 'Semestre ' . ($pCounter++),
            'start_time' => Carbon::now()->startOfDay(),
            'end_time' => Carbon::now()->addMonths(4)->endOfDay(),
            'mid_time' => Carbon::now()->addMonths(2)->midDay(),
            'is_active' => true,
        ], $attributes));
    }

    protected function createCourse(int $institutionId, array $attributes = []): Course
    {
        static $cCounter = 1;
        return Course::create(array_merge([
            'name' => 'Curso ' . ($cCounter++),
            'institution_id' => $institutionId,
        ], $attributes));
    }

    /**
     * Verifica que las cabeceras HTTP de seguridad (CSP, X-Frame-Options, X-Content-Type-Options) estén presentes.
     */
    public function test_security_headers_are_present_in_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'geolocation=(), camera=(), microphone=()');
        $this->assertNotEmpty($response->headers->get('Content-Security-Policy'));
    }

    /**
     * Verifica que los inputs sean sanitizados automáticamente eliminando scripts y etiquetas HTML.
     */
    public function test_input_sanitization_strips_dangerous_html_and_scripts(): void
    {
        $inst = $this->createInstitution();
        $student = $this->createStudent();
        $profesor = $this->createProfesor($inst);
        $period = $this->createPeriod();
        $course = $this->createCourse($inst->id);

        $maliciousComment = "<script>alert('xss');</script>Profesor muy <b>claro</b> y puntual.";

        $response = $this->actingAs($student, 'student')
            ->post(route('add_review', $profesor->id), [
                'calificacion' => 5,
                'contenido' => $maliciousComment,
                'curso' => $course->name,
                'period_id' => $period->id,
            ]);

        $resenia = Resenia::where('profesor_id', $profesor->id)->first();
        $this->assertNotNull($resenia);
        $this->assertStringNotContainsString('<script>', $resenia->contenido);
        $this->assertStringNotContainsString('</script>', $resenia->contenido);
        $this->assertStringNotContainsString('<b>', $resenia->contenido);
        $this->assertEquals("alert('xss');Profesor muy claro y puntual.", $resenia->contenido);
    }

    /**
     * Verifica Row Level Security: Un estudiante no puede editar una reseña perteneciente a otro estudiante.
     */
    public function test_row_level_security_prevents_editing_another_students_review(): void
    {
        $inst = $this->createInstitution();
        $ownerStudent = $this->createStudent();
        $attackerStudent = $this->createStudent();

        $profesor = $this->createProfesor($inst);
        $period = $this->createPeriod();
        $course = $this->createCourse($inst->id);

        $resenia = Resenia::create([
            'profesor_id' => $profesor->id,
            'period_id' => $period->id,
            'curso' => $course->name,
            'calificacion' => 4,
            'contenido' => 'Comentario del dueño original.',
        ]);
        $resenia->students()->attach($ownerStudent->id);

        // El atacante intenta editar la reseña del dueño
        $response = $this->actingAs($attackerStudent, 'student')
            ->put(route('resenia.editar', $resenia->id), [
                'calificacion' => 1,
                'contenido' => 'Intento malicioso de edición.',
                'curso' => $course->name,
                'period_id' => $period->id,
            ]);

        $response->assertStatus(403);

        // Verificar que el contenido en base de datos no fue alterado
        $resenia->refresh();
        $this->assertEquals('Comentario del dueño original.', $resenia->contenido);
    }

    /**
     * Verifica Row Level Security: Un estudiante no puede eliminar una reseña de otro estudiante.
     */
    public function test_row_level_security_prevents_deleting_another_students_review(): void
    {
        $inst = $this->createInstitution();
        $ownerStudent = $this->createStudent();
        $attackerStudent = $this->createStudent();

        $profesor = $this->createProfesor($inst);
        $period = $this->createPeriod();
        $course = $this->createCourse($inst->id);

        $resenia = Resenia::create([
            'profesor_id' => $profesor->id,
            'period_id' => $period->id,
            'curso' => $course->name,
            'calificacion' => 4,
            'contenido' => 'Reseña protegida.',
        ]);
        $resenia->students()->attach($ownerStudent->id);

        $response = $this->actingAs($attackerStudent, 'student')
            ->delete(route('resenia.eliminar', $resenia->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('resenias', ['id' => $resenia->id]);
    }

    /**
     * Verifica que el rate limiting bloquee intentos excesivos de inicio de sesión tras superar el límite.
     */
    public function test_rate_limiting_blocks_brute_force_login_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('login.student.post'), [
                'email' => 'victim@example.com',
                'password' => 'wrong-password',
            ]);
            $response->assertStatus(302); // Redirect back with error
        }

        // El sexto intento debe ser bloqueado con 429 Too Many Requests
        $blockedResponse = $this->post(route('login.student.post'), [
            'email' => 'victim@example.com',
            'password' => 'wrong-password',
        ]);

        $blockedResponse->assertStatus(429);
    }
}
