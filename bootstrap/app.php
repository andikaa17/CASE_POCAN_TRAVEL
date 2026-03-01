<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiKeyMiddleware; // <-- IMPORT MIDDLEWARE

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'api.key' => ApiKeyMiddleware::class,
        ]);
        
        // ===== TAMBAHKAN MIDDLEWARE CORS =====
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class, // <-- PENTING UNTUK CORS!
            'api.key',
        ]);
        
        // Atau kalo mau lebih sederhana:
        // $middleware->api->prepend(\Illuminate\Http\Middleware\HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();