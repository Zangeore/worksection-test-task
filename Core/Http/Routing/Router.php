<?php

namespace Core\Http\Routing;

class Router
{
    /**
     * @var array
     */
    protected $routes = [];

    public function add(string $method, string $path, array $handler): void
    {
        $method = strtoupper($method);
        $path = rtrim($path, '/') ?: '/';

        $pattern = preg_replace('#\{(\w+)\}#', '(?P<\1>[^/]+)', $path);
        $this->routes[$method][$path] = [
            'regex' => '#^' . $pattern . '$#',
            'handler' => $handler
        ];
    }

    public function resolve(string $method, string $path): ?array
    {
        $method = strtoupper($method);
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $route => $data) {
            if (preg_match($data['regex'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [$data['handler'][0], $data['handler'][1], $params];
            }
        }

        return null;
    }
}