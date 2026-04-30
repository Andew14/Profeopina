<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Student;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_login_via_student_form()
    {
        $student = Student::create([
            'name' => 'Alumno',
            'email' => 'alumno@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login.student.post'), [
            'email' => 'alumno@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('iniciologueado'));
        $this->assertTrue(auth()->guard('student')->check());
        $this->assertEquals('alumno@example.com', auth()->guard('student')->user()->email);
    }

    public function test_admin_user_can_login_via_student_form()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $admin = User::factory()->create(['email' => 'admin_unas@example.com', 'role' => 'admin', 'institution_id' => $inst->id]);

        $response = $this->post(route('login.student.post'), [
            'email' => 'admin_unas@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(auth()->check());
        $this->assertEquals('admin_unas@example.com', auth()->user()->email);
    }
}
