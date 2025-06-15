<?php

namespace Core;

use Core\DI\DIContainer;
use Core\Exception\ExceptionHandlerResolver;
use Core\Http\Exceptions\NotFoundHttpException;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Routing\Router;
use Core\Kernels\ConsoleKernel;
use Core\Kernels\KernelInterface;
use Core\Kernels\WebKernel;
use ReflectionException;
use ReflectionMethod;

class App
{
    /**
     * @var DIContainer
     */
    protected $diContainer;

    public function __construct(array $config = [])
    {
        $this->initialize($config);
    }

    protected function initialize(array $config): void
    {
        $config = array_merge_recursive(require __DIR__ . "/Config/config.php", $config);
        $this->diContainer = new DIContainer([
            'definitions' => $config['definitions'] ?? [],
            'factories' => $config['factories'] ?? [],
        ]);
        $defaultBootstrap = require __DIR__ . "/bootstrap.php";
        $defaultBootstrap($this);
        $customBootstrap = $config['bootstraps'] ?? [];
        foreach ($customBootstrap as $callable) {
            if (is_callable($callable)) {
                $callable($this);
            } else {
                throw new \InvalidArgumentException('Bootstrap must be a callable');
            }
        }
    }

    public function getDIContainer(): DIContainer
    {
        return $this->diContainer;
    }


    /**
     * @throws ReflectionException
     */
    public function run(): void
    {
        /** @var KernelInterface $kernel */
        $kernel = null;
        if ($this->runningInConsole()) {
            $kernel = $this->diContainer->get(ConsoleKernel::class);
        } else {
            $kernel = $this->diContainer->get(WebKernel::class);
        }
        $kernel->run();
    }

    protected function runningInConsole(): bool
    {
        return PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg';
    }


}