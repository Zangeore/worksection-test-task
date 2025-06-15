<?php

namespace Core\Exception;

use Core\Exception\Handlers\DefaultErrorHandler;
use Core\Http\Response;
use Throwable;

class ExceptionHandlerResolver
{
    /**
     * @var ExceptionHandlerInterface[]
     */
    protected $handlers = [];
    /**
     * @var ExceptionHandlerInterface|null
     */
    protected  $fallback = null;

    public function __construct(array $handlers = [], ?ExceptionHandlerInterface $fallback = null)
    {
        $this->handlers = $handlers;
        $this->fallback = $fallback ?? new DefaultErrorHandler();
    }

    public function resolve(Throwable $e): Response
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($e)) {
                return $handler->handle($e);
            }
        }

        return $this->fallback->handle($e);
    }
}