<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Exceptions\ApiExceptionHandler;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $handler = new ApiExceptionHandler();

        $exceptions->renderable(function (ModelNotFoundException $e, $request) use ($handler) {
            return $handler->handleModel($e, $request);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, $request) use ($handler) {

            $previous = $e->getPrevious();

            if ($previous instanceof ModelNotFoundException) {
                return $handler->handleModel($previous, $request);
            }

            return $handler->handleFallback($request);
        });

        $exceptions->renderable(function (AuthorizationException $e, $request) use ($handler) {
            return $handler->handleAuthorization($e, $request);
        });

        $exceptions->renderable(function (AccessDeniedHttpException $e, $request) use ($handler) {
            return $handler->handleAuthorization(
                new AuthorizationException($e->getMessage()),
                $request
            );
        });

    })
    ->create();