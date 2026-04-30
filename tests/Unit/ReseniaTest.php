<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Profesor;
use App\Models\Resenia;

class ReseniaTest extends TestCase
{
    use RefreshDatabase;

    public function test_resenia_belongs_to_profesor()
    {
        $profesor = Profesor::factory()->create();
        $resenia = Resenia::factory()->create(['profesor_id' => $profesor->id]);

        $this->assertInstanceOf(Profesor::class, $resenia->profesor);
        $this->assertEquals($profesor->id, $resenia->profesor->id);
    }

    public function test_resenia_default_calificacion()
    {
        $profesor = Profesor::factory()->create();
        // Create a resenia without specifying `calificacion` so the factory/default applies
        $resenia = Resenia::factory()->make(['profesor_id' => $profesor->id]);

        $resenia->save();

        $this->assertIsInt($resenia->calificacion);
        $this->assertGreaterThanOrEqual(0, $resenia->calificacion);
    }
}
