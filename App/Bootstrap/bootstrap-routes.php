<?php

use Core\Http\Routing\Router;
use Core\App;

return function (App $app)
{
    $routes = [
        require __DIR__ . '/../Routes/web.php',
    ];
    $diContainer = $app->getDIContainer();
    $router = $diContainer->get(Router::class);
    foreach ($routes as $route) {
        if (is_callable($route)) {
            $route($router);
        } else {
            throw new \InvalidArgumentException('Route must be a callable');
        }
    }

};
