<?php

namespace Core\Http;

class Request
{
    /**
     * @var array
     */
    protected $body;
    /**
     * @var array
     */
    protected $routeParams = [];

    public function __construct()
    {
        $this->body = $this->parseInput();
    }

    protected function parseInput(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'application/json') === 0) {
            $raw = file_get_contents('php://input');
            $json = json_decode($raw, true);
            return is_array($json) ? $json : [];
        }

        if (strpos($contentType, 'application/x-www-form-urlencoded') === 0) {
            $raw = file_get_contents('php://input');
            parse_str($raw, $data);
            return is_array($data) ? $data : [];
        }

        return $_POST;
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';
    }

    public function body(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->body;
        }
        return $this->body[$key] ?? $default;
    }

    public function query(string $key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    public function header(string $key): ?string
    {
        $normalized = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$normalized] ?? null;
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function route(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->routeParams;
        }
        return $this->routeParams[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->body, $_GET, $this->routeParams);
    }

}
