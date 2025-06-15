<?php

use App\Http\TodoController;
use Core\Http\Routing\Router;

return function (Router $router) {
    $router->add("GET", "/", [TodoController::class, "index"]);
};