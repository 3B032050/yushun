<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // 保留 ValidationException 原本處理
        if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        }

        // HTTP Exception
        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
            $message = $exception->getMessage() ?: null;

            // 尋找對應 Blade
            $view = view()->exists("errors.{$status}") ? "errors.{$status}" : 'errors.500';

            return response()->view($view, [
                'code' => $status,
                'message' => $message,
            ], $status);
        }

        // 非 HTTP Exception (Production)
        if (config('app.debug') === false) {
            return response()->view('errors.500', [
                'code' => 500,
                'message' => '系統發生錯誤，請稍後再試。',
            ], 500);
        }

        // 開發模式保留 Laravel 原生錯誤頁
        return parent::render($request, $exception);
    }
}
