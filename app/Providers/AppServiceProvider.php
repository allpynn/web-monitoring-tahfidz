<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        view()->composer('components.tahfidz.sidebar', function ($view) {
            if (auth()->check() && auth()->user()->role === 'guru') {
                $unreadCount = \App\Models\Pesan::where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->where('is_resolved', false)
                    ->select('student_id')
                    ->distinct()
                    ->get()
                    ->count(); 
                $view->with('unreadMessagesCount', $unreadCount);
            }
        });
    }
}
