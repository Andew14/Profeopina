<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        //
        Student::create([
            'name' => 'Andrew',
                'email' => 'student1@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
        ]);
        Student::create([
            'name' => 'Marco',
                'email' => 'student2@example.com',
                'password' => Hash::make('holamarco'),
                'created_at' => now(),
                'updated_at' => now(),
        ]);
        
    }
}
