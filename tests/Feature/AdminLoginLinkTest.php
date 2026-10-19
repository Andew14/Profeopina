<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_does_not_have_admin_link_for_security()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertDontSee('Eres Administrador')
            ->assertDontSee(route('login.admin'));
    }
}
