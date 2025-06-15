<?php

namespace Core\Database;

interface DatabaseInterface
{
    public function query(string $sql, array $params = []): array;
    public function execute(string $sql, array $params = []): int;
    public function lastInsertId(): string;
}