<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testEmail = env('TEST_STUDENT_EMAIL', 'alumno.prueba@unas.edu.pe');
        $testPassword = env('TEST_STUDENT_PASSWORD', 'AlumnoOpina2026!');

        Student::updateOrCreate(
            ['email' => $testEmail],
            [
                'name' => 'Alumno de Prueba',
                'password' => Hash::make($testPassword),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
