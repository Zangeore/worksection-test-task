<?php

namespace Core\Exception;

use Core\Http\Response;
use Throwable;

interface ExceptionHandlerInterface
{
    public function supports(Throwable $e): bool;
    public function handle(Throwable $e): Response;
}