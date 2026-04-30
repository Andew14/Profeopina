<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resenia;
use App\Models\Profesor;
use App\Models\Period;
use App\Models\Question;
use App\Models\Answer;

class ReseniasTableSeeder extends Seeder
{
    public function run()
    {
        $juan = Profesor::where('nombre', 'Juan')->first();
        $maria = Profesor::where('nombre', 'María')->first();
        $period = Period::first();

        $likert1 = Question::where('text', 'LIKE', '%capacidad pedagógica%')->first();
        $likert2 = Question::where('text', 'LIKE', '%participación%')->first();
        $textQuestion = Question::where('type', 'text')->first();

        if ($juan && $period) {
            $r1 = Resenia::create([
                'contenido' => 'Excelente profesor, explica muy bien los temas complejos.',
                'calificacion' => 5,
                'profesor_id' => $juan->id,
                'period_id' => $period->id,
            ]);

            if ($likert1) Answer::create(['resenia_id' => $r1->id, 'question_id' => $likert1->id, 'numeric_value' => 5]);
            if ($likert2) Answer::create(['resenia_id' => $r1->id, 'question_id' => $likert2->id, 'numeric_value' => 4]);
            if ($textQuestion) Answer::create(['resenia_id' => $r1->id, 'question_id' => $textQuestion->id, 'text_value' => 'La única queja es que a veces va muy rápido.']);
            
            $r2 = Resenia::create([
                'contenido' => 'Buen dominio, pero falta más paciencia.',
                'calificacion' => 4,
                'profesor_id' => $juan->id,
                'period_id' => $period->id,
            ]);

            if ($likert1) Answer::create(['resenia_id' => $r2->id, 'question_id' => $likert1->id, 'numeric_value' => 4]);
            if ($likert2) Answer::create(['resenia_id' => $r2->id, 'question_id' => $likert2->id, 'numeric_value' => 3]);
        }

        if ($maria && $period) {
            $r3 = Resenia::create([
                'contenido' => 'Sus clases de historia son interesantes pero los exámenes son muy difíciles.',
                'calificacion' => 3,
                'profesor_id' => $maria->id,
                'period_id' => $period->id,
            ]);

            if ($likert1) Answer::create(['resenia_id' => $r3->id, 'question_id' => $likert1->id, 'numeric_value' => 3]);
            if ($likert2) Answer::create(['resenia_id' => $r3->id, 'question_id' => $likert2->id, 'numeric_value' => 5]);
            if ($textQuestion) Answer::create(['resenia_id' => $r3->id, 'question_id' => $textQuestion->id, 'text_value' => 'Me gustaría más material de lectura guiado.']);
        }
    }
}
