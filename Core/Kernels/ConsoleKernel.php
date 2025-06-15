<?php

namespace Core\Kernels;

use Core\DI\DIContainer;

class ConsoleKernel implements KernelInterface
{
    /**
     * @var array
     */
    public $commands = [];
    /**
     * @var string
     */
    public $defaultCommand = 'help';

    /**
     * @var DIContainer
     */
    public $diContainer;

    public function __construct(DIContainer $diContainer, array $commands = [], string $defaultCommand = 'help')
    {
        $this->commands = $commands;
        $this->defaultCommand = $defaultCommand;
        $this->diContainer = $diContainer;
    }

    public function run()
    {
        $argv = $_SERVER['argv'];
        array_shift($argv);
        $command = array_shift($argv);
        if (!$command || !isset($this->commands[$command])) {
            $command = $this->commands[$this->defaultCommand];
        } else {
            $command = $this->commands[$command];
        }
        $this->diContainer->get($command)->run($argv);
    }
}