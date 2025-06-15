<?php

namespace Core\Exception\Handlers;

use Core\Exception\ExceptionHandlerInterface;
use Core\Http\Response;
use Throwable;

class DefaultErrorHandler implements ExceptionHandlerInterface
{

    public function supports(Throwable $e): bool
    {
        return true;
    }

    public function handle(Throwable $e): Response
    {
        if (PHP_SAPI === 'cli') {
            fwrite(STDERR, "❌  " . $e->getMessage() . "\n");

            fwrite(STDERR, "Exception: " . get_class($e) . "\n");
            fwrite(STDERR, "In: " . $e->getFile() . ':' . $e->getLine() . "\n");
            fwrite(STDERR, "--- Stack trace ---\n" . $e->getTraceAsString() . "\n");
            exit(1);
        }

        $responseData = [
            'error' => $e->getMessage(),
        ];

        $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

        return Response::json($responseData, $status);
    }
}