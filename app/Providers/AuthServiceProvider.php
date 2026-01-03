<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('approve-reservation', function (User $user) {
            $allowedRoles = [
                'Admin',
                'Technical Resource Manager',
                'Data Center Administrator',
                'Technical Manager'
            ];
            return $user->role && in_array($user->role->name, $allowedRoles);
        });

        Gate::define('create-reservation', function (User $user) {
            $allowedRoles = [
                'Admin',
                'Internal User',
                'Engineer',
                'Technical Resource Manager',
                'Technical Manager'
            ];
            return $user->role && in_array($user->role->name, $allowedRoles);
        });
    }
}
