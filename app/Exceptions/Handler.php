<?php

namespace App\Exceptions;

use App\Traits\JsonResponder;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

    public function render($request, Throwable $exception)
    {

        $exceptionClass = get_class($exception);
        $message = $exception->getMessage();

        switch ($exceptionClass) {
            case "Illuminate\Database\Eloquent\NotFoundHttpException":
                return JsonResponder::notFound('Route Not Found');

            case "Illuminate\Database\Eloquent\MethodNotAllowedHttpException":
                return JsonResponder::methodNotAllowed($exception->getMessage());

            case "Symfony\Component\HttpKernel\Exception\NotFoundHttpException":
                return JsonResponder::notFound('Resource Not Found');

            case "Illuminate\Database\Eloquent\ModelNotFoundException":
                return JsonResponder::notFound('Resource Not Found');

            case "App\Exceptions\UnauthorizedException":
                return JsonResponder::unauthenticated($exception->getMessage());

            case "Tymon\JWTAuth\Exceptions\TokenInvalidException":
                return JsonResponder::unauthenticated($exception->getMessage());

            case "Tymon\JWTAuth\Exceptions\TokenExpiredException":
                return JsonResponder::unauthenticated($exception->getMessage());

            case "Tymon\JWTAuth\Exceptions\JWTException":
                return JsonResponder::unauthenticated($exception->getMessage());

            case "Illuminate\Validation\ValidationException":
                return JsonResponder::validationError('Validation Failed', $exception->errors());

            case "Spatie\Permission\Exceptions\UnauthorizedException":
                return JsonResponder::forbidden('User does not have the right permissions.');
            default:
                return JsonResponder::internalServerError();
        }
    }
}
