<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Profesor;
use App\Models\Resenia;
use App\Models\Institution;
use App\Models\Period;
use App\Models\Course;

class SemaphorizedMetricsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('questions')->insert([
            ['id' => 1, 'text' => 'Claridad de explicación y comunicación', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'text' => 'Justicia en la Evaluación y Retroalimentación', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'text' => 'Empatía y Relación interpersonal', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'text' => 'Conocimiento y Dominio de la materia', 'type' => 'likert', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_teacher_profile_renders_semaphorized_metrics_badges()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        Course::create(['name' => 'Matemáticas', 'institution_id' => $inst->id]);
        $prof = Profesor::create([
            'nombre' => 'María',
            'apellido' => 'Curie',
            'institution_id' => $inst->id,
            'activo' => true,
        ]);
        $period = Period::create(['name' => '2026-I', 'is_active' => true]);

        $res1 = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 5,
            'contenido' => 'Excelente profesora, explica muy claro y domina la materia.',
            'curso' => 'Matemáticas',
            'period_id' => $period->id,
            'procesada' => true,
        ]);

        // Insertar respuesta destacada (verde = 5) para dimensión 1 (Claridad)
        DB::table('answers')->insert([
            'resenia_id' => $res1->id,
            'question_id' => 1,
            'numeric_value' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $res2 = Resenia::create([
            'profesor_id' => $prof->id,
            'calificacion' => 1,
            'contenido' => 'Exámenes muy injustos y difíciles.',
            'curso' => 'Matemáticas',
            'period_id' => $period->id,
            'procesada' => true,
        ]);

        // Insertar respuesta deficiente (rojo = 1) para dimensión 2 (Justicia)
        DB::table('answers')->insert([
            'resenia_id' => $res2->id,
            'question_id' => 2,
            'numeric_value' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('perfil.profesor', $prof->id));

        $response->assertStatus(200);
        $response->assertSee('Métricas Detalladas (IA)');
        $response->assertSee('🔴 Deficiente');
        $response->assertSee('🟡 Neutral');
        $response->assertSee('🟢 Destacado');
    }
}
