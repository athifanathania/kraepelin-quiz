<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        // Atur default password minimal 6 karakter
        Password::defaults(function () {
            return Password::min(6); // <--- UBAH DISINI (Defaultnya 8)
        });
    }
}
