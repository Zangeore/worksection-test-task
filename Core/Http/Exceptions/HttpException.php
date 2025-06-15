<?php

namespace Core\Http\Exceptions;

use Throwable;

class HttpException extends \Exception
{

    public function __construct($message = "Unexpected error occurred", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }

}