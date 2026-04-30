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
        Student::firstOrCreate(
            ['email' => 'student1@example.com'],
            [
                'name' => 'Andrew',
                'password' => Hash::make('123456789'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        Student::firstOrCreate(
            ['email' => 'student2@example.com'],
            [
                'name' => 'Marco',
                'password' => Hash::make('123456789'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
    }
}
