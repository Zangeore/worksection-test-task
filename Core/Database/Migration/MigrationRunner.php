<?php

namespace Core\Database\Migration;

use Core\Database\DatabaseInterface;

class MigrationRunner
{
    protected $db;
    protected $basePath;

    public function __construct(
        DatabaseInterface $db,
        string $basePath
    ) {
        $this->basePath = $basePath;
        $this->db = $db;
    }

    public function run(): void
    {
        $this->ensureTable();

        $applied = $this->getApplied();

        $files = glob($this->basePath . '/*.php');
        sort($files);

        foreach ($files as $file) {
            $name = basename($file);

            if (in_array($name, $applied, true)) {
                continue;
            }

            $migration = require $file;

            if (!$migration instanceof MigrationInterface) {
                throw new \RuntimeException("Migration $name must return instance of MigrationInterface");
            }

            echo "Applying $name...\n";
            $migration->up($this->db);

            $this->db->execute("INSERT INTO migrations (name) VALUES (?)", [$name]);
        }
    }

    public function rollback(): void
    {
        $this->ensureTable();

        $last = $this->db->query("SELECT name FROM migrations ORDER BY applied_at DESC LIMIT 1");

        if (empty($last)) {
            echo "No migration to rollback.\n";
            return;
        }

        $name = $last[0]['name'];
        $file = $this->basePath . '/' . $name;

        if (!file_exists($file)) {
            throw new \RuntimeException("Migration file not found: $name");
        }

        $migration = require $file;

        if (!$migration instanceof MigrationInterface) {
            throw new \RuntimeException("Migration $name must return instance of MigrationInterface");
        }

        echo "Rolling back $name...\n";
        $migration->down($this->db);

        $this->db->execute("DELETE FROM migrations WHERE name = ?", [$name]);
    }

    protected function getApplied(): array
    {
        $this->ensureTable();
        return array_column($this->db->query("SELECT name FROM migrations"), 'name');
    }

    protected function ensureTable(): void
    {
        $this->db->execute("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE,
                applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
}