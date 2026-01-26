<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share notification data with the layout view automatically
      View::composer('layouts.app', function ($view) {
            $notifications = collect([]); // Empty by default

            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                
                // Fetch unread notifications using your custom relationship
                $notifications = $user->notifications()
                                      ->where('is_read', false)
                                      ->orderBy('created_at', 'desc')
                                      ->get();
            }
                                            
            $view->with('unreadNotifications', $notifications);
        });

        // =================================================================
        // 2. ROLE PERMISSIONS (Gates)
        // =================================================================
        // These are used for @can('create-reservation') in Blade
        // and Gate::allows(...) in Controllers.

        // Who can create reservations? (User & Admin)
        Gate::define('create-reservation', function (User $user) {
            return $user->role->name === 'utilisateur_interne' 
                || $user->role->name === 'admin';
        });

        // Who can approve/reject? (Manager & Admin)
        Gate::define('approve-reservation', function (User $user) {
            return $user->role->name === 'responsable_technique' 
                || $user->role->name === 'admin';
        });

        // Who can manage users/resources? (Admin only)
        Gate::define('manage-users', function (User $user) {
            return $user->role->name === 'admin';
        });
    }
}