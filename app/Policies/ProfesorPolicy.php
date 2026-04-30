<?php

namespace App\Policies;

use App\Models\Profesor;
use App\Models\User;

class ProfesorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Profesor $profesor): bool
    {
        return $user->isAdmin() && $user->institution_id === $profesor->institution_id;
    }

    public function toggleActive(User $user, Profesor $profesor): bool
    {
        return $this->update($user, $profesor);
    }
}