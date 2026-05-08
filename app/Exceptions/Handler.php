<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $debug = config('app.debug');

        // Ошибки валидации — 422
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'code'    => 422,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        }

        // Не аутентифицирован — 401
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'code'    => 401,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Нет прав — 403
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'code'    => 403,
                'message' => $e->getMessage() ?: 'Forbidden',
            ], 403);
        }

        // Модель не найдена — 404
        if ($e instanceof ModelNotFoundException) {
            $model = class_basename($e->getModel());
            return response()->json([
                'success' => false,
                'code'    => 404,
                'message' => "{$model} not found",
            ], 404);
        }

        // Роут не найден — 404
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'code'    => 404,
                'message' => 'Route not found',
            ], 404);
        }

        // Метод не разрешён — 405
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'code'    => 405,
                'message' => 'Method not allowed',
            ], 405);
        }

        // Любое другое HTTP-исключение (abort(403), abort(503) и т.д.)
        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            return response()->json([
                'success' => false,
                'code'    => $status,
                'message' => $e->getMessage() ?: 'HTTP error',
            ], $status);
        }

        // Всё остальное — 500
        $response = [
            'success' => false,
            'code'    => 500,
            'message' => $debug ? $e->getMessage() : 'Server error',
        ];

        if ($debug) {
            $response['exception'] = get_class($e);
            $response['file']      = $e->getFile();
            $response['line']      = $e->getLine();
            $response['trace']     = array_slice(
                array_map(fn($f) => ($f['file'] ?? '?') . ':' . ($f['line'] ?? '?'), $e->getTrace()),
                0, 15
            );
        }

        return response()->json($response, 500);
    }
}
