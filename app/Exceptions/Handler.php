<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport
        = [
            //
        ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash
        = [
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
        $this->reportable(function (Throwable $e)
        {
            //
        });
    }


    /**
     * @param Request   $request
     * @param Throwable $exception
     *
     * @return JsonResponse|Response
     */
    public function render($request, Throwable $exception)
    {
        $code = $exception->getCode() ? $exception->getCode() : 500;

        if ($exception instanceof AuthorizationException)
        {
            $code = 403;
        }

        $message = [
            'message' => $exception->getMessage(),
            'code'    => $code,
        ];

        return response()->json($message, $code);
    }
}
