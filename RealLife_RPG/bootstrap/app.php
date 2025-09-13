<?php

use App\Http\Middleware\AdminRoleMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api();
        $middleware->alias([
            'admin.role' => AdminRoleMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 401 Unauthorized
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'error' => 'Unauthenticated',
                'code'  => 401,
                'message' => $e->getMessage(),
            ], 401);
        });

        // 403 Forbidden
        $exceptions->render(function (AuthorizationException $e, $request) {
            return response()->json([
                'error' => 'Forbidden',
                'code'  => 403,
                'message' => $e->getMessage(),
            ], 403);
        });

        // 404 Not Found
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'error' => 'Resource not found',
                'code'  => 404,
            ], 404);
        });

        // 405 Method Not Allowed
        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'error' => 'Method not allowed',
                'code'  => 405,
                'message' => $e->getMessage(),
            ], 405);
        });

        // 500 Internal Server Error (catch all)
        $exceptions->render(function (Throwable $e, $request) {
            return response()->json([
                'error' => 'Server error',
                'code'  => 500,
                'message' => $e->getMessage(),
            ], 500);
        });
    })
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['api', 'auth:sanctum']],
    )
    ->create();
