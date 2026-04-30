<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Profesor;
use App\Models\Resenia;
use App\Models\Institution;

class AdminReseniaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_toggle_hidden_on_resenia()
    {
        $inst = Institution::create(['name' => 'Inst X']);
        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);

        $prof = Profesor::factory()->create(['institution_id' => $inst->id]);
        $res = Resenia::factory()->create(['profesor_id' => $prof->id, 'oculto' => false]);

        $response = $this->actingAs($admin)->post(route('admin.resenias.toggleHidden', $res));

        $response->assertStatus(200);
        $this->assertDatabaseHas('resenias', ['id' => $res->id, 'oculto' => true]);
    }

    public function test_admin_cannot_toggle_hidden_for_resenia_from_other_institution()
    {
        $inst = Institution::create(['name' => 'Inst A']);
        $inst2 = Institution::create(['name' => 'Inst B']);

        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);
        $prof = Profesor::factory()->create(['institution_id' => $inst2->id]);
        $res = Resenia::factory()->create(['profesor_id' => $prof->id, 'oculto' => false]);

        $response = $this->actingAs($admin)->post(route('admin.resenias.toggleHidden', $res));

        $response->assertStatus(403);
        $this->assertDatabaseHas('resenias', ['id' => $res->id, 'oculto' => false]);
    }
}
