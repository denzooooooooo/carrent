<?php

use App\Http\Middleware\NoCacheMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //Enregistrer les middlewares globaux ici
        $middleware->web(append: [
            NoCacheMiddleware::class, // Ajoute l'anti-cache à toutes les routes web
        ]);

        $middleware->alias([
            'guest:admin' => \App\Http\Middleware\RedirectIfAdminAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
