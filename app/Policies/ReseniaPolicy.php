<?php

namespace App\Policies;

use App\Models\Resenia;
use App\Models\User;

class ReseniaPolicy
{
    public function toggleHidden(User $user, Resenia $resenia): bool
    {
        return $user->isAdmin() && $user->institution_id === $resenia->profesor->institution_id;
    }
}