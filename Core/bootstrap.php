<?php

use Core\App;
use Core\DI\DIContainer;
use Core\Exception\ExceptionHandlerResolver;
use Core\Http\Exceptions\HttpException;

return static function (App $app) {
    $diContainer = $app->getDIContainer();

    /** @var ExceptionHandlerResolver $exceptionHandlerResolver */
    $exceptionHandlerResolver = $diContainer->get(ExceptionHandlerResolver::class);

    set_error_handler(static function () use ($exceptionHandlerResolver) {
        $exceptionHandlerResolver->resolve(new HttpException())->send();
    });

    set_exception_handler(static function ($exception) use ($exceptionHandlerResolver) {
        $exceptionHandlerResolver->resolve($exception)->send();
    });

    register_shutdown_function(static function () use ($exceptionHandlerResolver) {
        $error = error_get_last();
        if ($error) {
            $exceptionHandlerResolver->resolve(new HttpException())->send();
        }
    });

    $diContainer->set(DIContainer::class, $diContainer);
};