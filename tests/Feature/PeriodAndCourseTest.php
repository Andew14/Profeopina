<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Institution;
use App\Models\Period;
use App\Models\Course;
use App\Models\Student;
use App\Models\Profesor;
use App\Models\Resenia;
use App\Models\User;
use Carbon\Carbon;

class PeriodAndCourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_create_overlapping_academic_periods()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create initial period: 2026-01-01 to 2026-06-30
        Period::create([
            'name' => 'Ciclo 2026-I',
            'start_time' => Carbon::parse('2026-01-01')->startOfDay(),
            'end_time' => Carbon::parse('2026-06-30')->endOfDay(),
            'mid_time' => Carbon::parse('2026-03-31')->midDay(),
            'is_active' => true,
        ]);

        // Attempt to create overlapping period: 2026-06-15 to 2026-12-15
        $response = $this->actingAs($admin)
            ->post(route('admin.periods.store'), [
                'name' => 'Ciclo 2026-II (Overlapping)',
                'start_time' => '2026-06-15',
                'end_time' => '2026-12-15',
                'is_active' => '0',
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('periods', [
            'name' => 'Ciclo 2026-II (Overlapping)'
        ]);
    }

    public function test_only_predefined_courses_can_be_selected_in_reviews()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $student = Student::create([
            'name' => 'John Student',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $prof = Profesor::create([
            'nombre' => 'Walter',
            'apellido' => 'White',
            'descripcion' => 'Chemistry Teacher',
            'institution_id' => $inst->id,
            'activo' => true,
        ]);

        $period = Period::create([
            'name' => '2026-I',
            'is_active' => true,
        ]);

        // Create a valid course
        Course::create([
            'name' => 'Química Orgánica I',
            'institution_id' => $inst->id,
        ]);

        // Submit review with invalid course
        $response = $this->actingAs($student, 'student')
            ->post(route('add_review', $prof->id), [
                'calificacion' => 5,
                'contenido' => 'Excelente profesor.',
                'curso' => 'Curso Inexistente',
                'period_id' => $period->id,
            ]);

        $response->assertSessionHasErrors(['curso']);
        $this->assertDatabaseMissing('resenias', [
            'profesor_id' => $prof->id,
            'curso' => 'Curso Inexistente',
        ]);

        // Submit review with valid course
        $response2 = $this->actingAs($student, 'student')
            ->post(route('add_review', $prof->id), [
                'calificacion' => 5,
                'contenido' => 'Excelente profesor.',
                'curso' => 'Química Orgánica I',
                'period_id' => $period->id,
            ]);

        $response2->assertRedirect();
        $this->assertDatabaseHas('resenias', [
            'profesor_id' => $prof->id,
            'curso' => 'Química Orgánica I',
        ]);
    }

    public function test_search_professor_by_full_name()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        
        Profesor::create([
            'nombre' => 'René',
            'apellido' => 'Descartes',
            'descripcion' => 'Philosopher',
            'institution_id' => $inst->id,
            'activo' => true,
        ]);

        // Search by first name only
        $response = $this->get(route('buscar.profesor', ['profesor' => 'René']));
        $response->assertStatus(200);
        $response->assertSee('René');

        // Search by full name (first name + space + last name)
        $response2 = $this->get(route('buscar.profesor', ['profesor' => 'René Descartes']));
        $response2->assertStatus(200);
        $response2->assertSee('René');
    }
}
