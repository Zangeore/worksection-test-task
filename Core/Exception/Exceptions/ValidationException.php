<?php

namespace Core\Exception\Exceptions;

use Core\Http\Exceptions\HttpException;

class ValidationException extends HttpException
{
    /**
     * @var array
     */
    protected $errors;

    public function __construct(array $errors = [], int $code = 422, \Throwable $previous = null)
    {
        parent::__construct('Validation failed', $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
