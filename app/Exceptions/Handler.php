<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $error    = $this->convertExceptionToResponse($exception);
        $response = [];

        $response['message'] = $exception->getMessage();
        $response['code']    = $error->getStatusCode();

        if ($exception instanceof NotFoundHttpException)
        {
            $response['message'] = 'Page not found';
        }

        // Model not found
        if ($exception instanceof ModelNotFoundException)
        {
            $response['code'] = 404;

            if (!Config::get('app.debug'))
            {
                $response['message'] = 'Entity not found.';
            }
        }

        // Trace
        if (Config::get('app.debug'))
        {
            $response['trace'] = $exception->getTraceAsString();
        }

        return response()->json($response, $error->getStatusCode());
    }
}
