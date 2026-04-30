<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Profesor;
use App\Models\Institution;

class AdminProfesorTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_profesor()
    {
        $inst = Institution::create(['name' => 'Inst X']);
        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);

        $response = $this->actingAs($admin)->post(route('admin.profesors.store'), [
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'descripcion' => 'Profesor de Matemáticas',
        ]);

        $response->assertRedirect(route('admin.profesors.index'));

        $this->assertDatabaseHas('profesors', [
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'institution_id' => $inst->id,
        ]);
    }

    public function test_admin_can_toggle_active_on_own_institution_profesor()
    {
        $inst = Institution::create(['name' => 'Inst X']);
        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);
        $prof = Profesor::factory()->create(['institution_id' => $inst->id, 'activo' => true]);

        $response = $this->actingAs($admin)->post(route('admin.profesors.toggleActive', $prof));

        $response->assertStatus(200);
        $this->assertDatabaseHas('profesors', ['id' => $prof->id, 'activo' => false]);
    }

    public function test_admin_cannot_toggle_active_for_other_institution_profesor()
    {
        $inst = Institution::create(['name' => 'Inst A']);
        $inst2 = Institution::create(['name' => 'Inst B']);

        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);
        $prof = Profesor::factory()->create(['institution_id' => $inst2->id, 'activo' => true]);

        $response = $this->actingAs($admin)->post(route('admin.profesors.toggleActive', $prof));

        $response->assertStatus(403);
        $this->assertDatabaseHas('profesors', ['id' => $prof->id, 'activo' => true]);
    }
}
