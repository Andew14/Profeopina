<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Student;

class StudentUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_sees_student_links_and_not_guest_links()
    {
        $student = Student::create(['name' => 'Alumno', 'email' => 'alumno@example.com', 'password' => bcrypt('password')]);

        $response = $this->actingAs($student, 'student')->get(route('inicio'));

        $response->assertStatus(200);
        $response->assertDontSee('Iniciar sesión');
        $response->assertDontSee('Admin Login');
        $response->assertSee('Cerrar sesión');
        $response->assertSee('Perfil');
    }
}
