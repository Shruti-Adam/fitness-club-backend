<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    // ✅ Middleware Configuration
    ->withMiddleware(function (Middleware $middleware) {

        // IMPORTANT:
        // Removed statefulApi() because React uses token authentication
        // This avoids CSRF token mismatch errors

        // Force API authentication behaviour
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions) {

        // Handle Unauthenticated (Sanctum)
        $exceptions->render(function (AuthenticationException $e, Request $request) {

            if ($request->expectsJson() || $request->is('api/*')) {

                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);

            }

        });

        // Handle Other API Errors (safe version)
        $exceptions->render(function (\Throwable $e, Request $request) {

            if ($request->is('api/*')) {

                return response()->json([
                    'message' => $e->getMessage(),
                    'error'   => class_basename($e)
                ], 500);

            }

        });

    })
    ->create();