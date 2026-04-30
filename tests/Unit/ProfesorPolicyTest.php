<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Profesor;
use App\Models\Institution;
use App\Policies\ProfesorPolicy;

class ProfesorPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_same_institution_can_update_and_toggle_active()
    {
        $inst = Institution::create(['name' => 'Test Institution']);
        $user = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);
        $profesor = Profesor::factory()->create(['institution_id' => $inst->id]);

        $policy = new ProfesorPolicy();

        $this->assertTrue($policy->update($user, $profesor));
        $this->assertTrue($policy->toggleActive($user, $profesor));
    }

    public function test_admin_different_institution_cannot_update()
    {
        $inst = Institution::create(['name' => 'Inst A']);
        $inst2 = Institution::create(['name' => 'Inst B']);

        $user = User::factory()->create(['role' => 'admin', 'institution_id' => $inst2->id]);
        $profesor = Profesor::factory()->create(['institution_id' => $inst->id]);

        $policy = new ProfesorPolicy();

        $this->assertFalse($policy->update($user, $profesor));
    }

    public function test_non_admin_same_institution_cannot_update()
    {
        $inst = Institution::create(['name' => 'Inst A']);
        $user = User::factory()->create(['role' => 'user', 'institution_id' => $inst->id]);
        $profesor = Profesor::factory()->create(['institution_id' => $inst->id]);

        $policy = new ProfesorPolicy();

        $this->assertFalse($policy->update($user, $profesor));
    }
}
