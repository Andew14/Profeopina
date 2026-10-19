<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Institution;

class AdminUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_redirected_to_dashboard_from_inicio()
    {
        $inst = Institution::create(['name' => 'UNAS']);
        $admin = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);

        $response = $this->actingAs($admin)->get(route('inicio'));

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_guest_sees_guest_sidebar_links()
    {
        $response = $this->get(route('inicio'));
        $response->assertStatus(200);
        $response->assertSee('Inicio');
        $response->assertSee('Iniciar sesión');
        $response->assertSee('Registrarse');
        $response->assertDontSee('Admin Login');
    }
}
