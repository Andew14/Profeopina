<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiamos los IDs del 1 al 4 si ya existieran para evitar colisiones
        DB::table('questions')->whereIn('id', [1, 2, 3, 4])->delete();

        DB::table('questions')->insert([
            ['id' => 1, 'text' => 'Claridad de explicación y comunicación', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'text' => 'Justicia en la Evaluación y Retroalimentación', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'text' => 'Empatía y Relación interpersonal', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'text' => 'Conocimiento y Dominio de la materia', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
