<?php

use App\Http\Middleware\RequireJson;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function() {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api_v1.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(ThrottleRequestsException | NotFoundHttpException $e) {
            return response()->json([
                'message' => config('messages.errors.'.$e->getStatusCode())
            ], $e->getStatusCode());
        });

        $exceptions->render(function(Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'server error.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    })->create();
