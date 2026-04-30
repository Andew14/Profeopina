<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_has_admin_link()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('Eres Administrador')
            ->assertSee(route('login.admin'));
    }
}
