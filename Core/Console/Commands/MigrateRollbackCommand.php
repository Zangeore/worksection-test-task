<?php

namespace Core\Console\Commands;

use Core\Database\Migration\MigrationRunner;
use Core\DI\DIContainer;

class MigrateRollbackCommand implements CommandInterface
{

    /**
     * @var DIContainer
     */
    protected $diContainer;

    public function __construct(DIContainer $diContainer)
    {
        $this->diContainer = $diContainer;
    }

    public function run()
    {
        $this->diContainer->get(MigrationRunner::class)->run();
    }
}