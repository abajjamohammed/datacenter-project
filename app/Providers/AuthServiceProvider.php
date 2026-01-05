<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Model policies can go here later
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /**
         * CREATE RESERVATION
         * Any normal authenticated user with allowed role
         */
        Gate::define('create-reservation', function (User $user) {
            if (!$user->role) {
                return false;
            }

            return in_array($user->role->name, [
                'Admin',
                'Internal User',
                'Engineer',
                'Technical Resource Manager',
                'Technical Manager',
            ]);
        });

        /**
         * APPROVE RESERVATION
         * Only higher-level roles
         */
        Gate::define('approve-reservation', function (User $user) {
            if (!$user->role) {
                return false;
            }

            return in_array($user->role->name, [
                'Admin',
                'Technical Resource Manager',
                'Data Center Administrator',
                'Technical Manager',
            ]);
        });
    }
}
