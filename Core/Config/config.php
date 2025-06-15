<?php

use Core\Console\Commands\HelpCommand;
use Core\Console\Commands\MigrateRollbackCommand;
use Core\Console\Commands\MigrateUpCommand;
use Core\Database\DatabaseInterface;
use Core\Database\Migration\MigrationRunner;
use Core\Database\PdoDatabase;
use Core\Exception\ExceptionHandlerResolver;
use Core\Exception\Handlers\DefaultErrorHandler;
use Core\Http\Render\Php\PhpViewRenderer;
use Core\Http\Render\RendererInterface;
use Core\Kernels\ConsoleKernel;

return [
    'definitions' => [
        ExceptionHandlerResolver::class => [
            'class' => ExceptionHandlerResolver::class,
            'arguments' => [
                'handlers' => [
                ],
                'fallback' => new DefaultErrorHandler()
            ],
        ],
        ConsoleKernel::class => [
            'class' => ConsoleKernel::class,
            'arguments' => [
                'commands' => [
                   'help' => HelpCommand::class,
                    'migrate:up' => MigrateUpCommand::class,
                    'migrate:rollback' => MigrateRollbackCommand::class,
                ],
            ],
        ],

    ],
];