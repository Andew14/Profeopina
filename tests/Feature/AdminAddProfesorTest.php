<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAddProfesorTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_create_profesor_form()
    {
        $institution = Institution::factory()->create();
        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $institution->id]);

        $this->actingAs($admin)
             ->get(route('admin.profesors.create'))
             ->assertStatus(200)
             ->assertSee('nombre');
    }

    public function test_admin_can_create_profesor()
    {
        $institution = Institution::factory()->create();
        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $institution->id]);

        $response = $this->actingAs($admin)
            ->post(route('admin.profesors.store'), [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'descripcion' => 'Profesor de Matemáticas',
            ]);

        // For now, just verify the profesor was created in the database
        $this->assertDatabaseHas('profesors', [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'institution_id' => $institution->id,
        ]);
    }
}
