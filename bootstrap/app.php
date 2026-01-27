<?php

use App\Exceptions\Auth\EmailSenderException;
use App\Exceptions\Auth\TokenGenerationException;
use App\Exceptions\Auth\TokenStorageException;
use App\Exceptions\Auth\TokenValidationException;
use App\Exceptions\Auth\UserStorageException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenGenerationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        });

        $exceptions->render(function (TokenStorageException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        });

        $exceptions->render(function (EmailSenderException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        });

        $exceptions->render(function (TokenValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        });

        $exceptions->render(function (UserStorageException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], $e->getCode());
        });
    })->create();
