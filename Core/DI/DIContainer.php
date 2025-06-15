<?php

namespace Core\DI;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use RuntimeException;

class DIContainer
{
    /**
     * @var array
     */
    protected $definitions = [];
    /**
     * @var array
     */
    protected $instances = [];
    /**
     * @var array
     */
    protected $factories = [];

    public function __construct($config = [])
    {
        if (isset($config['definitions']) && is_array($config['definitions'])) {
            $this->definitions = $config['definitions'];
        }

        if (isset($config['factories']) && is_array($config['factories'])) {
            $this->factories = $config['factories'];
        }
    }

    /**
     * @param string $id
     * @param array|string $definition
     * @return void
     */
    public function define(string $id, $definition): void
    {
        $this->definitions[$id] = $definition;
    }

    public function bind(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function set(string $id, object $instance): void
    {
        $this->instances[$id] = $instance;
    }

    public function has(string $id): bool
    {
        return isset($this->instances[$id]);
    }

    /**
     * @throws ReflectionException
     * @return mixed
     */
    public function get(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->factories[$id])) {
            return $this->instances[$id] = $this->factories[$id]($this);
        }

        $definition = $this->definitions[$id] ?? $id;

        if (is_array($definition)) {
            $class = $definition['class'] ?? $id;
            $args = $definition['arguments'] ?? [];
            $shared = $definition['shared'] ?? true;
            $object = $this->build($class, $args);
            if ($shared) {
                $this->instances[$id] = $object;
            }
            return $object;
        }

        return $this->instances[$id] = $this->build($definition);
    }

    /**
     * @throws ReflectionException
     */
    public function build(string $class, $baseParams = []): object
    {
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found.");
        }

        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class;
        }

        $params = [];
        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            if (isset($baseParams[$name])) {
                $params[] = $baseParams[$name];
                continue;
            }
            $type = $param->getType();
            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $params[] = $this->get($type->getName());
            } elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                throw new RuntimeException("Can't resolve parameter ${$name} for $class");
            }
        }
        return $reflection->newInstanceArgs($params);
    }

}