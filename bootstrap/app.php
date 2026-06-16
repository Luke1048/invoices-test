<?php

use App\Exceptions\InvalidAmountException;
use App\Exceptions\InvoiceNotFoundException;
use App\Exceptions\InvoiceNotUpdatableException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (InvoiceNotFoundException $e) {
            return response()->json([
                'exception' => class_basename($e),
                'message' => __('invoice.not_found', ['id' => $e->id]),
            ], 404);
        });

        $exceptions->render(function (InvoiceNotUpdatableException $e) {
            return response()->json([
                'exception' => class_basename($e),
                'message' => __('invoice.wrong_status', ['id' => $e->id]),
            ], 422);
        });

        $exceptions->render(function (InvalidAmountException $e) {
            return response()->json([
                'exception' => class_basename($e),
                'message' => __('invoice.invalid_amount'),
            ], 422);
        });
    })->create();
