<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        // Admin
        $gate->define('isOnlyAdmin', function ($user) {
            return $user->type == 'admin';
        });
    }
}
