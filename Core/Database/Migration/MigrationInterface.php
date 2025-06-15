<?php

namespace Core\Database\Migration;

use Core\Database\DatabaseInterface;

interface MigrationInterface
{
    public function up(DatabaseInterface $db): void;
    public function down(DatabaseInterface $db): void;
}