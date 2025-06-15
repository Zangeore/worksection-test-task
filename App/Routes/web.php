<?php

use App\Http\TodoController;
use Core\Http\Routing\Router;

return function (Router $router) {
    $router->add("GET", "/", [TodoController::class, "index"]);
    $router->add('POST', '/todo', [TodoController::class, 'store']);
    $router->add('PUT', '/todo/{id}', [TodoController::class, 'update']);
    $router->add('DELETE', '/todo/{id}', [TodoController::class, 'destroy']);
};
