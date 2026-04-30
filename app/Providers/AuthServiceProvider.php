<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Profesor;
use App\Models\Resenia;
use App\Policies\ProfesorPolicy;
use App\Policies\ReseniaPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Profesor::class => ProfesorPolicy::class,
        Resenia::class => ReseniaPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
