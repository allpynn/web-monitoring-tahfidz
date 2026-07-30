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
        // Register Model Observers for Real-Time Event Broadcasting
        \App\Models\Student::observe(\App\Observers\StudentObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\RiwayatHafalan::observe(\App\Observers\RiwayatHafalanObserver::class);

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
