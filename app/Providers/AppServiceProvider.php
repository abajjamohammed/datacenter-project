<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share notification data with the layout view automatically
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                
                // Get unread notifications
                $unreadNotifications = $user->notifications()
                                            ->where('is_read', false)
                                            ->orderBy('created_at', 'desc')
                                            ->get();
                                            
                $view->with('unreadNotifications', $unreadNotifications);
            }
        });
    }
}