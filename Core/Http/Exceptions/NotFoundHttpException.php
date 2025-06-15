<?php

namespace Core\Http\Exceptions;

class NotFoundHttpException extends HttpException
{

    public function __construct($message = "Resource not found", $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return 404;
    }
}