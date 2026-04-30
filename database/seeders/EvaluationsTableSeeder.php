<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Period;
use App\Models\Question;

class EvaluationsTableSeeder extends Seeder
{
    public function run()
    {
        $period = Period::create([
            'name' => 'Semestre 2026-I',
            'start_time' => now(),
            'end_time' => now()->addMonths(4),
            'is_active' => true,
        ]);

        Question::create([
            'text' => '¿Cómo evalúa la capacidad pedagógica del docente?',
            'type' => 'likert',
            'options' => ['Muy deficiente', 'Deficiente', 'Regular', 'Suficiente', 'Excelente'],
            'is_active' => true,
        ]);

        Question::create([
            'text' => '¿El docente fomenta la participación de los alumnos en clase?',
            'type' => 'likert',
            'options' => ['Nunca', 'Casi Nunca', 'A veces', 'Casi Siempre', 'Siempre'],
            'is_active' => true,
        ]);

        Question::create([
            'text' => 'Comentario Privado (Solo Administración UNAS)',
            'type' => 'text',
            'is_active' => true,
        ]);
    }
}
