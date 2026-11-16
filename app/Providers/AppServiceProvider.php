<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\URL;

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
        // Soal 3: Gate kelola-buku hanya mengizinkan role admin dan petugas
        Gate::define('kelola-buku', function (User $user) {
            return $user->hasAnyRole(['admin', 'petugas']);
        });

        if ($this->app->environment('production')) {
        \URL::forceScheme('https');
        }

    {
        if (config('app.env') !== 'local' || isset($_SERVER['VERCEL'])) {
            URL::forceScheme('https');
        }
    }
    }
}