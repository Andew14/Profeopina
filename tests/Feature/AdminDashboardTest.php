<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_loads_with_admin_sidebar()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
             ->get(route('admin.dashboard'))
             ->assertStatus(200)
             ->assertSee(trans('messages.admin_dashboard_title'))
             ->assertSee(route('admin.profesors.create'));
    }
}
