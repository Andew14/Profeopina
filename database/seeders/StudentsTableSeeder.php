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
        $students = [
            ['email' => 'andrew@example.com', 'name' => 'Andrew'],
            ['email' => 'marco@example.com', 'name' => 'Marco'],
            ['email' => 'luis@example.com', 'name' => 'Luis Rojas'],
            ['email' => 'ana@example.com', 'name' => 'Ana Torres'],
            ['email' => 'diego@example.com', 'name' => 'Diego Flores'],
            ['email' => 'sofia@example.com', 'name' => 'Sofía Medina'],
            ['email' => 'carlos@example.com', 'name' => 'Carlos Vargas'],
            ['email' => 'gabriela@example.com', 'name' => 'Gabriela Silva'],
            ['email' => 'mateo@example.com', 'name' => 'Mateo Castro'],
            ['email' => 'lucia@example.com', 'name' => 'Lucía Mendoza'],
        ];

        foreach ($students as $data) {
            Student::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('123456789'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
