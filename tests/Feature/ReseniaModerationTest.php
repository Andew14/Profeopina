<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Student;
use App\Models\Profesor;
use App\Models\Resenia;
use App\Models\Institution;
use App\Models\Period;
use App\Models\Question;

class ReseniaModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_add_review_with_stars_and_comment_only()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        \App\Models\Course::create(['name' => 'Química', 'institution_id' => $inst->id]);
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

        // Add dynamic questions in database to ensure they are ignored and don't cause errors
        Question::create(['text' => 'Capacidad pedagógica', 'type' => 'likert', 'is_active' => true]);

        $response = $this->actingAs($student, 'student')
            ->post(route('add_review', $prof->id), [
                'calificacion' => 4,
                'contenido' => 'Excelente profesor y gran pedagogo.',
                'curso' => 'Química',
                'period_id' => $period->id,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('resenias', [
            'profesor_id' => $prof->id,
            'calificacion' => 4,
            'contenido' => 'Excelente profesor y gran pedagogo.',
            'period_id' => $period->id,
            'curso' => 'Química',
            'denunciada' => false,
            'oculto' => false,
        ]);
    }

    public function test_rating_average_includes_hidden_reviews_for_authenticity()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $prof = Profesor::create([
            'nombre' => 'Walter',
            'apellido' => 'White',
            'institution_id' => $inst->id,
            'activo' => true,
        ]);

        // Add 2 reviews: one visible (5 stars) and one hidden (1 star)
        Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 5,
            'contenido' => 'El mejor de todos.',
            'oculto' => false,
        ]);

        Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 1,
            'contenido' => 'Llega muy tarde y es grosero.',
            'oculto' => true,
        ]);

        // Access the public profile as a guest
        $response = $this->get(route('perfil.profesor', $prof->id));

        $response->assertStatus(200);
        // Average should be (5 + 1) / 2 = 3.0 stars (not 5.0)
        $response->assertSee('3.0');
    }

    public function test_only_registered_student_can_report_review()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $prof = Profesor::create(['nombre' => 'Walter', 'apellido' => 'White', 'institution_id' => $inst->id]);
        $res = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 5,
            'contenido' => 'Buen profesor.',
            'denunciada' => false,
        ]);

        // 1. Guest user attempt -> fails / rejects with error
        $guestResponse = $this->post(route('resenias.denunciar', $res->id));
        $guestResponse->assertRedirect();
        $guestResponse->assertSessionHas('error');
        $this->assertDatabaseHas('resenias', [
            'id' => $res->id,
            'denunciada' => false,
        ]);

        // 2. Logged in student attempt -> succeeds
        $student = Student::create([
            'name' => 'Jane Student',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
        ]);

        $studentResponse = $this->actingAs($student, 'student')
            ->post(route('resenias.denunciar', $res->id));

        $studentResponse->assertRedirect();
        $studentResponse->assertSessionHas('success');
        $this->assertDatabaseHas('resenias', [
            'id' => $res->id,
            'denunciada' => true,
        ]);
    }

    public function test_admin_can_dismiss_reported_review()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);
        $prof = Profesor::create(['nombre' => 'Walter', 'apellido' => 'White', 'institution_id' => $inst->id]);
        
        $res = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 2,
            'contenido' => 'Comentario con insultos.',
            'denunciada' => true,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.resenias.dismissReport', $res->id));

        $response->assertStatus(200);
        $response->assertJson(['denunciada' => false]);
        $this->assertDatabaseHas('resenias', [
            'id' => $res->id,
            'denunciada' => false,
        ]);
    }
}
