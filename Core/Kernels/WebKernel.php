<?php

namespace Core\Kernels;

use Core\DI\DIContainer;
use Core\Exception\ExceptionHandlerResolver;
use Core\Http\Exceptions\NotFoundHttpException;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Routing\Router;
use ReflectionException;
use ReflectionMethod;

class WebKernel implements KernelInterface
{
    /**
     * @var DIContainer
     */
    private $diContainer;

    public function __construct(DIContainer $container)
    {
        $this->diContainer = $container;
    }

    /**
     * @throws ReflectionException
     */
    protected function callAction(object $controller, string $method, Request $request): Response
    {
        $reflection = new ReflectionMethod($controller, $method);
        $args = [];

        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();
            $name = $param->getName();

            if ($type && !$type->isBuiltin()) {
                $className = $type->getName();

                if ($className === Request::class) {
                    $args[] = $request;
                    continue;
                }

                $args[] = $this->diContainer->get($className);
                continue;
            }

            $value =
                $request->route($name)
                ?? $request->body($name)
                ?? $request->query($name);

            if ($value !== null) {
                $args[] = $value;
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }

            throw new \RuntimeException("Cannot resolve parameter \${$name} for method {$method} in class " . get_class($controller));
        }

        $response = $controller->$method(...$args);

        if (!$response instanceof Response) {
            throw new \RuntimeException("Method {$method} in class " . get_class($controller) . " must return an instance of Response");
        }

        return $response;
    }

    public function run()
    {
        try {
            $request = $this->diContainer->get(Request::class);
            $router = $this->diContainer->get(Router::class);

            $result = $router->resolve($request->method(), $request->path());

            if (!$result) {
                throw new NotFoundHttpException(
                    sprintf('No route found for %s %s', $request->method(), $request->path())
                );
            }

            [$controllerClass, $method, $params] = $result;

            $request->setRouteParams($params);

            $controller = $this->diContainer->build($controllerClass);
            $response = $this->callAction($controller, $method, $request);
            $response->send();

        } catch (\Throwable $e) {
            $resolver = $this->diContainer->get(ExceptionHandlerResolver::class);
            $resolver->resolve($e)->send();
        }
    }
}