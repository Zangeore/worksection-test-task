<?php

use Core\Database\Migration\MigrationRunner;
use Core\Http\Render\Php\PhpViewRenderer;
use Core\Http\Render\RendererInterface;

return [
    'bootstraps' => [
        require __DIR__ . '/../Bootstrap/bootstrap-routes.php',
    ],
    'definitions' => [
        RendererInterface::class => [
            'class' => PhpViewRenderer::class,
            'arguments' => [
               'basePath' => __DIR__. '/../../resources/views',
            ],
        ],
        MigrationRunner::class => [
            'class' => MigrationRunner::class,
            'arguments' => [
                'basePath' => __DIR__.'../../database/migrations'
            ]
        ]
    ]
];