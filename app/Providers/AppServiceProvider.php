<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Usar Bootstrap 5 para la paginación (activa nuestro vendor/pagination/bootstrap-5.blade.php)
        Paginator::useBootstrapFive();
    }
}
