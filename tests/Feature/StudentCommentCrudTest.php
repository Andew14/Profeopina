<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Student;
use App\Models\Profesor;
use App\Models\Resenia;
use App\Models\Period;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;

class StudentCommentCrudTest extends TestCase
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

    public function test_student_can_edit_own_pending_review()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        \App\Models\Course::create(['name' => 'Física I', 'institution_id' => $inst->id]);
        \App\Models\Course::create(['name' => 'Física II', 'institution_id' => $inst->id]);
        $student = Student::create(['name' => 'John', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
        $prof = Profesor::create(['nombre' => 'Walter', 'apellido' => 'White', 'institution_id' => $inst->id]);
        $period1 = Period::create(['name' => '2026-I', 'is_active' => true]);
        $period2 = Period::create(['name' => '2026-II', 'is_active' => false]);

        $res = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 3,
            'contenido' => 'Regular.',
            'curso' => 'Física I',
            'period_id' => $period1->id,
            'procesada' => false,
        ]);
        $res->students()->attach($student->id);

        $response = $this->actingAs($student, 'student')
            ->from(route('perfil.profesor', $prof->id))
            ->put(route('resenia.editar', $res->id), [
                'calificacion' => 5,
                'contenido' => 'Actualizado: Excelente docente.',
                'curso' => 'Física II',
                'period_id' => $period2->id,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('perfil.profesor', $prof->id));
        $this->assertDatabaseHas('resenias', [
            'id' => $res->id,
            'calificacion' => 5,
            'contenido' => 'Actualizado: Excelente docente.',
            'curso' => 'Física II',
            'period_id' => $period2->id,
        ]);
    }

    public function test_student_cannot_edit_others_review()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        \App\Models\Course::create(['name' => 'Física I', 'institution_id' => $inst->id]);
        \App\Models\Course::create(['name' => 'Física II', 'institution_id' => $inst->id]);
        $student1 = Student::create(['name' => 'John', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
        $student2 = Student::create(['name' => 'Jane', 'email' => 'jane@example.com', 'password' => bcrypt('password')]);
        $prof = Profesor::create(['nombre' => 'Walter', 'apellido' => 'White', 'institution_id' => $inst->id]);
        $period = Period::create(['name' => '2026-I', 'is_active' => true]);

        $res = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 3,
            'contenido' => 'Regular.',
            'curso' => 'Física I',
            'period_id' => $period->id,
            'procesada' => false,
        ]);
        $res->students()->attach($student1->id);

        $response = $this->actingAs($student2, 'student')
            ->from(route('perfil.profesor', $prof->id))
            ->put(route('resenia.editar', $res->id), [
                'calificacion' => 5,
                'contenido' => 'Hackeado.',
                'curso' => 'Física II',
                'period_id' => $period->id,
            ]);

        $response->assertStatus(403);
    }

    public function test_student_can_edit_processed_review_with_option_a()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        \App\Models\Course::create(['name' => 'Química', 'institution_id' => $inst->id]);
        \App\Models\Course::create(['name' => 'Química II', 'institution_id' => $inst->id]);
        $student = Student::create(['name' => 'John', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
        $prof = Profesor::create(['nombre' => 'Walter', 'apellido' => 'White', 'institution_id' => $inst->id]);
        $period = Period::create(['name' => '2026-I', 'is_active' => true]);

        $res = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 4,
            'contenido' => 'Comentario original.',
            'curso' => 'Química',
            'period_id' => $period->id,
            'procesada' => true,
        ]);
        $res->students()->attach($student->id);

        $response = $this->actingAs($student, 'student')
            ->from(route('perfil.profesor', $prof->id))
            ->put(route('resenia.editar', $res->id), [
                'calificacion' => 5,
                'contenido' => 'Actualizado con Opción A.',
                'curso' => 'Química II',
                'period_id' => $period->id,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('perfil.profesor', $prof->id));
        $this->assertDatabaseHas('resenias', [
            'id' => $res->id,
            'calificacion' => 5,
            'contenido' => 'Actualizado con Opción A.',
            'curso' => 'Química II',
            'procesada' => true,
        ]);
    }

    public function test_student_can_delete_pending_review()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $student = Student::create(['name' => 'John', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
        $prof = Profesor::create(['nombre' => 'Walter', 'apellido' => 'White', 'institution_id' => $inst->id]);
        $period = Period::create(['name' => '2026-I', 'is_active' => true]);

        $res = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 3,
            'contenido' => 'Eliminar esto.',
            'curso' => 'Física',
            'period_id' => $period->id,
            'procesada' => false,
        ]);
        $res->students()->attach($student->id);

        $response = $this->actingAs($student, 'student')
            ->from(route('perfil.profesor', $prof->id))
            ->delete(route('resenia.eliminar', $res->id));

        $response->assertRedirect(route('perfil.profesor', $prof->id));
        $this->assertDatabaseMissing('resenias', ['id' => $res->id]);
    }

    public function test_student_deleting_processed_review_clears_content_but_keeps_rating()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $student = Student::create(['name' => 'John', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
        $prof = Profesor::create(['nombre' => 'Walter', 'apellido' => 'White', 'institution_id' => $inst->id]);
        $period = Period::create(['name' => '2026-I', 'is_active' => true]);

        $res = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 5,
            'contenido' => 'Comentario de alta importancia.',
            'curso' => 'Física',
            'period_id' => $period->id,
            'procesada' => true,
        ]);
        $res->students()->attach($student->id);

        // Crear una respuesta IA asociada
        DB::table('answers')->insert([
            'resenia_id' => $res->id,
            'question_id' => 1,
            'numeric_value' => 5,
        ]);

        $response = $this->actingAs($student, 'student')
            ->from(route('perfil.profesor', $prof->id))
            ->delete(route('resenia.eliminar', $res->id));

        $response->assertRedirect(route('perfil.profesor', $prof->id));
        
        // La reseña sigue existiendo en BD para fines estadísticos, pero el contenido es un placeholder
        $this->assertDatabaseHas('resenias', [
            'id' => $res->id,
            'contenido' => '[Comentario eliminado por el estudiante]',
            'calificacion' => 5,
        ]);

        // La respuesta del análisis NLP sigue registrada
        $this->assertDatabaseHas('answers', [
            'resenia_id' => $res->id,
            'question_id' => 1,
            'numeric_value' => 5,
        ]);
    }
}
