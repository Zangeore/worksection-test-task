<?php

namespace Core\Database;

use PDO;

class PdoDatabase implements DatabaseInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct(string $dsn, string $username, string $password, array $options = [])
    {
        $default = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        $this->pdo = new PDO($dsn, $username, $password, $options + $default);
    }

    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}