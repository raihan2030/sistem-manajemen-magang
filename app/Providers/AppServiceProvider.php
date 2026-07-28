<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notifikasi;

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
        View::composer('layouts.sidebarAdmin', function ($view) {
            $unreadCount = 0;

            if (Auth::check() && Auth::user()->skpd_id) {
                $unreadCount = Notifikasi::forSkpd(Auth::user()->skpd_id)
                    ->belumDibaca()
                    ->count();
            }

            $view->with('unreadCount', $unreadCount);
        });
    }
}
