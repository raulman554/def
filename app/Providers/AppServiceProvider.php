<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;          // ← importar Router
use App\Http\Middleware\IsAdmin;        // ← importar nuestro middleware

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(Router $router): void
    {
        /*
         |------------------------------------------------------------
         | Alias is_admin (por si Kernel no lo está cargando)
         |------------------------------------------------------------
         */
        if (! $router->hasMiddlewareGroup('is_admin')) {
            $router->aliasMiddleware('is_admin', IsAdmin::class);
        }
    }
}
