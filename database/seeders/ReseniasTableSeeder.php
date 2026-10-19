<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Resenia;
use App\Models\Profesor;
use App\Models\Period;
use App\Models\Student;
use App\Jobs\ProcessReviewNLP;

class ReseniasTableSeeder extends Seeder
{
    public function run()
    {
        // Limpiar datos previos
        DB::table('answers')->delete();
        DB::table('users_resenias')->delete();
        DB::table('resenias')->delete();

        $period = Period::where('name', '2026-II')->first() ?? Period::where('is_active', true)->first() ?? Period::create(['name' => '2026-II', 'is_active' => true]);
        $periodPast = Period::where('name', '2026-I')->first() ?? $period;

        // Cargar profesores reales de la UNAS
        $nilton = Profesor::where('nombre', 'Nilton')->first();
        $noel = Profesor::where('nombre', 'Noel')->first();
        $edwin = Profesor::where('nombre', 'Edwin Jesús')->first();
        $brian = Profesor::where('nombre', 'Brian César')->first();

        // Cargar estudiantes
        $students = Student::all();
        if ($students->count() < 10) {
            return;
        }

        $reseniasToProcess = [];

        // --- 1. NILTON CHUCOS BAQUERIZO (Machine Learning / Gobierno TI) ---
        if ($nilton) {
            $reseniasToProcess[] = $this->createResenia(
                'El Ing. Nilton explica con gran claridad los modelos de Machine Learning. Sus prácticas son retadoras pero muy didácticas. Domina totalmente la materia.',
                5,
                $nilton->id,
                $students[0]->id,
                $period->id,
                'Machine Learning'
            );

            $reseniasToProcess[] = $this->createResenia(
                'Buena didáctica en Planeamiento de TI. Muy comprensivo con las entregas si avanzas constantemente en los laboratorios.',
                5,
                $nilton->id,
                $students[1]->id,
                $period->id,
                'Planeamiento y Gobierno de TI'
            );
        }

        // --- 2. NOEL JUIPA CAMPO (Diseño de Base de Datos / Prog. Básica) ---
        if ($noel) {
            $reseniasToProcess[] = $this->createResenia(
                'Excelente profesor en modelado de base de datos. Enseña Normalización y SQL paso a paso. Criterio justo en las evaluaciones.',
                5,
                $noel->id,
                $students[2]->id,
                $period->id,
                'Diseño de Base de Datos'
            );

            $reseniasToProcess[] = $this->createResenia(
                'Buenas explicaciones de la lógica de programación. Resuelve todas las dudas en clase y tiene bastante paciencia.',
                4,
                $noel->id,
                $students[3]->id,
                $period->id,
                'Programación Básica'
            );
        }

        // --- 3. EDWIN JESÚS VEGA VENTOCILLA (Gestión Proyectos TI) ---
        if ($edwin) {
            $reseniasToProcess[] = $this->createResenia(
                'El Ing. Vega exige bastante en la gestión de proyectos y documentación PMBOK/Scrum. Exámenes exigentes pero aprendes a trabajar bajo presión.',
                4,
                $edwin->id,
                $students[4]->id,
                $period->id,
                'Gestión de Proyectos de TI'
            );

            $reseniasToProcess[] = $this->createResenia(
                'Da muy buen seguimiento a las prácticas pre profesionales y brinda orientaciones realistas sobre el mercado laboral.',
                5,
                $edwin->id,
                $students[5]->id,
                $period->id,
                'Prácticas Pre Profesional'
            );
        }

        // --- 4. BRIAN CÉSAR PANDO SOTO (Desarrollo Apps Nube) ---
        if ($brian) {
            $reseniasToProcess[] = $this->createResenia(
                'Las clases de desarrollo en la nube con Docker y AWS son muy actualizadas. Explicación directa al grano y proyectos de código reales.',
                5,
                $brian->id,
                $students[6]->id,
                $period->id,
                'Desarrollo de Aplicaciones para la Nube'
            );
        }

        // Procesar automáticamente todas las reseñas mediante el Job oficial ProcessReviewNLP
        foreach ($reseniasToProcess as $resenia) {
            ProcessReviewNLP::dispatchSync($resenia);
        }
    }

    protected function createResenia(string $contenido, int $calificacion, int $profesorId, int $studentId, int $periodId, string $curso = 'Curso General', bool $oculto = false, bool $denunciada = false): Resenia
    {
        $resenia = Resenia::create([
            'contenido' => $contenido,
            'calificacion' => $calificacion,
            'profesor_id' => $profesorId,
            'period_id' => $periodId,
            'curso' => $curso,
            'oculto' => $oculto,
            'denunciada' => $denunciada,
            'procesada' => false,
        ]);

        DB::table('users_resenias')->insert([
            'user_id' => $studentId,
            'resenia_id' => $resenia->id,
        ]);

        return $resenia;
    }
}
