<?php

namespace App\Exceptions;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Exceptions\DomainExceptions\Entity\Validation\ValidationException;
use App\Exceptions\DomainExceptions\ImproveDomainException;
use App\Exceptions\DomainExceptions\Security\AccessDeniedException;
use App\Exceptions\DomainExceptions\Security\UnauthorizedException;
use DomainException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
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
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    private $logger;
    private $request;

    public function __construct(Container $container, LoggerInterface $logger, Request $request)
    {
        parent::__construct($container);
        $this->logger = $logger;
        $this->request = $request;
    }

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse|\Illuminate\Http\Response|Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($this->request->getRequestUri() === '/admin') {
            return parent::render($request, $exception);
        }

        $response = [];
        $response['status'] = Response::HTTP_INTERNAL_SERVER_ERROR;
        $response['instance'] = $this->request->getRequestUri();
        $response['title'] = 'Error';
        $response['detail'] = 'Error';
        $response['type'] = 'https://tools.ietf.org/html/rfc2616#section-10';
        $response['invalid_params'] = [];
        $response['additional_params'] = [];

        if ($exception instanceof NotFoundHttpException) {
            $response['status'] = Response::HTTP_NOT_FOUND;
            $response['detail'] = "Unknown endpoint";
        }

        if ($exception instanceof DomainException) {
            $response['status'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            $response['detail'] = $exception->getMessage();
        }

        if ($exception instanceof ThrottleRequestsException) {
            $response['status'] = Response::HTTP_TOO_MANY_REQUESTS;
            $response['detail'] = $exception->getMessage();
        }

        if ($exception instanceof ImproveDomainException) {
            $response['detail'] = $exception->getMessage();
            $response['invalid_params'] = $exception->getErrors();
            $response['additional_params'] = $exception->getAdditionalParams();

            if ($exception instanceof UnauthorizedException) {
                $response['status'] = Response::HTTP_UNAUTHORIZED;
            }

            if ($exception instanceof EntityNotFoundException) {
                $response['status'] = Response::HTTP_NOT_FOUND;
            }

            if ($exception instanceof AccessDeniedException) {
                $response['status'] = Response::HTTP_FORBIDDEN;
            }

            if ($exception instanceof ValidationException) {
                $response['status'] = Response::HTTP_UNPROCESSABLE_ENTITY;
            }
        } else {
            $this->logger->log(
                LogLevel::CRITICAL,
                sprintf("%s%s%s", $exception->getMessage(), PHP_EOL, $exception->getTraceAsString())
            );
        }

        if (config('app.debug')) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        return response()->json(
            $response, $response['status'], ['Content-Type' => 'application/problem+json']
        );
    }
}
