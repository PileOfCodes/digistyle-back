<?php

namespace App\Exceptions;

use App\Traits\apiResponse;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use apiResponse;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if($e instanceof ModelNotFoundException)
        {
            return $this->errorResponse($e->getMessage(),404);
        }
        if($e instanceof NotFoundHttpException)
        {
            return $this->errorResponse($e->getMessage(),404);
        }
        if($e instanceof MethodNotAllowedException)
        {
            return $this->errorResponse($e->getMessage(),404);
        }
        if($e instanceof Error)
        {
            return $this->errorResponse($e->getMessage(),500);
        }
        if($e instanceof Exception)
        {
            return $this->errorResponse($e->getMessage(),404);
        }
        return $this->errorResponse($e->getMessage(),500);
    }
}
