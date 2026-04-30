<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_loads()
    {
        $this->get(route('login.admin'))
             ->assertStatus(200)
             ->assertSee(trans('messages.admin_login_title'));
    }
}
