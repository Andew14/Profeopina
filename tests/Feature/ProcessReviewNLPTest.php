<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Profesor;
use App\Models\Resenia;
use App\Models\Institution;
use App\Models\Period;
use App\Jobs\ProcessReviewNLP;

class ProcessReviewNLPTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Asegurar que las 4 dimensiones fijas existan para los tests
        DB::table('questions')->insert([
            ['id' => 1, 'text' => 'Claridad de explicación y comunicación', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'text' => 'Justicia en la Evaluación y Retroalimentación', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'text' => 'Empatía y Relación interpersonal', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'text' => 'Conocimiento y Dominio de la materia', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_job_is_dispatched_immediately_when_adding_review()
    {
        Queue::fake();

        $inst = Institution::create(['name' => 'UNAS']);
        \App\Models\Course::create(['name' => 'Química', 'institution_id' => $inst->id]);
        $student = Student::create([
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $prof = Profesor::create([
            'nombre' => 'Walter',
            'apellido' => 'White',
            'institution_id' => $inst->id,
            'activo' => true,
        ]);

        $period = Period::create(['name' => '2026-I', 'is_active' => true]);

        $response = $this->actingAs($student, 'student')
            ->post(route('add_review', $prof->id), [
                'calificacion' => 5,
                'contenido' => 'Excelente profesor, domina totalmente el tema y explica genial.',
                'curso' => 'Química',
                'period_id' => $period->id,
            ]);

        $response->assertRedirect();
        // El Job se despacha inmediatamente al agregar el comentario
        Queue::assertPushed(ProcessReviewNLP::class);
        $this->assertDatabaseHas('resenias', [
            'profesor_id' => $prof->id,
            'curso' => 'Química',
            'period_id' => $period->id,
        ]);
    }

    public function test_job_fallback_logic_extracts_correct_dimensions_from_text()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $prof = Profesor::create([
            'nombre' => 'Walter',
            'apellido' => 'White',
            'institution_id' => $inst->id,
            'activo' => true,
        ]);

        // Escribimos una reseña con palabras clave de: explicación (claridad), califica (justicia) y empatía
        $resenia = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 4,
            'contenido' => 'Explica muy bien los temas prácticos, pero es muy estricto al calificar en los exámenes. Trato aceptable.',
            'oculto' => false,
        ]);

        // Sin servidor local activo o con error, el Job usará el fallback de palabras clave
        \Illuminate\Support\Facades\Http::fake([
            'http://127.0.0.1:8001/*' => \Illuminate\Support\Facades\Http::response(null, 500),
        ]);
        putenv('GEMINI_API_KEY='); 

        $job = new ProcessReviewNLP($resenia);
        $job->handle();

        // 1. Claridad de Explicación (ID 1): Debería estar poblado (> 3 ya que es positivo)
        $this->assertDatabaseHas('answers', [
            'resenia_id' => $resenia->id,
            'question_id' => 1,
        ]);
        $claridadValue = DB::table('answers')->where('resenia_id', $resenia->id)->where('question_id', 1)->value('numeric_value');
        $this->assertGreaterThanOrEqual(3, $claridadValue);

        // 2. Justicia en la Evaluación (ID 2): Debería ser bajo (1-2) porque contiene "estricto"
        $this->assertDatabaseHas('answers', [
            'resenia_id' => $resenia->id,
            'question_id' => 2,
        ]);
        $justiciaValue = DB::table('answers')->where('resenia_id', $resenia->id)->where('question_id', 2)->value('numeric_value');
        $this->assertLessThanOrEqual(2, $justiciaValue);

        // 3. Empatía (ID 3): Debería estar poblado porque menciona "trato"
        $this->assertDatabaseHas('answers', [
            'resenia_id' => $resenia->id,
            'question_id' => 3,
        ]);
    }

    public function test_job_calls_nlp_server_when_available()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $prof = Profesor::create([
            'nombre' => 'Walter',
            'apellido' => 'White',
            'institution_id' => $inst->id,
            'activo' => true,
        ]);

        $resenia = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 5,
            'contenido' => 'Excelente docente en todos los sentidos.',
            'oculto' => false,
        ]);

        // Simulamos la respuesta de nuestro servidor local de NLP (BERT)
        Http::fake([
            '127.0.0.1:8001/analizar' => Http::response([
                'claridad_comunicacion' => 5,
                'justicia_evaluacion' => 4,
                'empatia_relacion' => 5,
                'dominio_materia' => 5,
            ], 200)
        ]);

        $job = new ProcessReviewNLP($resenia);
        $job->handle();

        // Verificar que las respuestas se insertaron exactamente como devolvió la IA
        $this->assertDatabaseHas('answers', ['resenia_id' => $resenia->id, 'question_id' => 1, 'numeric_value' => 5]);
        $this->assertDatabaseHas('answers', ['resenia_id' => $resenia->id, 'question_id' => 2, 'numeric_value' => 4]);
        $this->assertDatabaseHas('answers', ['resenia_id' => $resenia->id, 'question_id' => 3, 'numeric_value' => 5]);
        $this->assertDatabaseHas('answers', ['resenia_id' => $resenia->id, 'question_id' => 4, 'numeric_value' => 5]);
    }
}
