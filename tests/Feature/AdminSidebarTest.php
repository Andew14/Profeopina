<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSidebarTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_does_not_see_admin_links_on_home()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
             ->get('/')
             ->assertStatus(200)
             ->assertDontSee(route('admin.profesors.index'))
             ->assertDontSee(route('admin.resenias.index'));
    }

    public function test_guest_does_not_see_admin_links()
    {
        $this->get('/')
             ->assertStatus(200)
             ->assertDontSee(route('admin.profesors.index'))
             ->assertDontSee(route('admin.resenias.index'));
    }
}
