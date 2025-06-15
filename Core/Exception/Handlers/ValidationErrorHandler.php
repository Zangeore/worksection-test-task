<?php

namespace Core\Exception\Handlers;

use Core\Exception\ExceptionHandlerInterface;
use Core\Exception\Exceptions\ValidationException;
use Core\Http\Response;
use Throwable;

class ValidationErrorHandler implements ExceptionHandlerInterface
{

    public function supports(Throwable $e): bool
    {
        return $e instanceof ValidationException;
    }

    public function handle(Throwable $e): Response
    {
        return Response::json([
            'message' => $e->getMessage(),
            'errors' => $e->getErrors(),
        ], $e->getCode() ?: 422);
    }
}
