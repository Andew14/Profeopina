<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Resenia;
use App\Models\Profesor;
use App\Models\Institution;
use App\Policies\ReseniaPolicy;

class ReseniaPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_same_institution_can_toggle_hidden()
    {
        $inst = Institution::create(['name' => 'Test Inst']);
        $user = User::factory()->create(['role' => 'admin', 'institution_id' => $inst->id]);
        $profesor = Profesor::factory()->create(['institution_id' => $inst->id]);
        $resenia = Resenia::factory()->create(['profesor_id' => $profesor->id]);

        $policy = new ReseniaPolicy();

        $this->assertTrue($policy->toggleHidden($user, $resenia));
    }

    public function test_admin_different_institution_cannot_toggle_hidden()
    {
        $inst = Institution::create(['name' => 'Inst A']);
        $inst2 = Institution::create(['name' => 'Inst B']);

        $user = User::factory()->create(['role' => 'admin', 'institution_id' => $inst2->id]);
        $profesor = Profesor::factory()->create(['institution_id' => $inst->id]);
        $resenia = Resenia::factory()->create(['profesor_id' => $profesor->id]);

        $policy = new ReseniaPolicy();

        $this->assertFalse($policy->toggleHidden($user, $resenia));
    }

    public function test_non_admin_cannot_toggle_hidden_even_in_same_institution()
    {
        $inst = Institution::create(['name' => 'Inst A']);
        $user = User::factory()->create(['role' => 'user', 'institution_id' => $inst->id]);
        $profesor = Profesor::factory()->create(['institution_id' => $inst->id]);
        $resenia = Resenia::factory()->create(['profesor_id' => $profesor->id]);

        $policy = new ReseniaPolicy();

        $this->assertFalse($policy->toggleHidden($user, $resenia));
    }
}
