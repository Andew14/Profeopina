<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Student;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_form_is_accessible()
    {
        $response = $this->get(route('login.admin'));
        $response->assertStatus(200);
        $response->assertSee('Administrador') // Looking for the Spanish "Administrador" text or HTML structure instead
            ->assertSee('login-container');
    }

    public function test_admin_can_login_via_admin_form()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $admin = User::factory()->create([ 'email' => 'admin_unas@example.com', 'role' => 'admin', 'institution_id' => $inst->id ]);

        $response = $this->post(route('login.admin.post'), [
            'email' => 'admin_unas@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard')); // Redirect to dashboard, not profesors index
        $this->assertTrue(auth()->check());
        $this->assertEquals('admin_unas@example.com', auth()->user()->email);
    }

    public function test_student_cannot_login_via_admin_form()
    {
        $student = Student::create([ 'name' => 'Alumno', 'email' => 'alumno@example.com', 'password' => Hash::make('password') ]);

        $response = $this->post(route('login.admin.post'), [
            'email' => 'alumno@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(auth()->check());
    }
}
