<?php

namespace Core\Console\Commands;

use Core\Kernels\ConsoleKernel;

class HelpCommand implements CommandInterface
{

    /**
     * @var ConsoleKernel
     */
    private $kernel;

    public function __construct(ConsoleKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function run()
    {
        echo "Usage: php public/index.php <command>\n";
        echo "Available commands:\n";
        foreach (array_keys($this->kernel->commands) as $name) {
            echo "  - $name\n";
        }
    }
}